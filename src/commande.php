<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once './config/Database.php';
require_once './models/Commandes.php';
require_once './models/Fidelite.php';

// Exiger une session active
if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
} else {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$commande = new Commande($db);
$fidelite = new Fidelite($db);

$stmt = $commande->getCurrentCommande($client_id);
$stmt_items = null;

// Détection du mode invité
$is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true;

// Initialisation des variables
$remise_active = null; 
$montant_reduction = 0;
$cout_points_remise = 0;
$prix_final = 0;

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_cmd = $row['commande_id'];
        
        // Sécurisation du prix initial (évite les erreurs si la colonne est vide)
        $prix_initial = isset($row['prix_total_remise']) ? $row['prix_total_remise'] : 0;
        
        // Sécurisation de l'ID restaurant
        $resto_id = isset($row['restaurant_id']) ? $row['restaurant_id'] : null;

        // --- CORRECTION 1 : Syntaxe !$is_guest (avec le $) ---
        if (!$is_guest && $resto_id) {
            $solde = $fidelite->getSolde($client_id, $resto_id);
            
            // --- CORRECTION 2 : On utilise le nom attendu par la vue ('solde_points_actuel') ---
            $row['solde_points_actuel'] = $solde;
            
            // --- CORRECTION 3 : On utilise le nom attendu par la vue ('remises_possibles') ---
            $row['remises_possibles'] = $fidelite->getRemisesDisponibles($resto_id, $solde);

            // Gestion du clic "Utiliser"
            if (isset($_GET['use_remise']) && is_numeric($_GET['use_remise'])) {
                $id_remise = $_GET['use_remise'];
                foreach ($row['remises_possibles'] as $r) {
                    if ($r['remise_id'] == $id_remise) {
                        if ($r['type_remise'] == 'pourcentage_remise') {
                            $montant_reduction = $prix_initial * ($r['pourcentage'] / 100);
                        } 
                        elseif ($r['type_remise'] == 'item_offert') {
                            $montant_reduction = $r['prix_item_offert'];
                        }
                        $cout_points_remise = $r['seuil_points'];
                        break;
                    }
                }
            }
        } else {
            // Invité
            $row['solde_points_actuel'] = 0;
            $row['remises_possibles'] = [];
        }

        // Calculs finaux
        $prix_final = $prix_initial - $montant_reduction;
        if ($prix_final < 0) $prix_final = 0;

        // Injection des données pour la vue
        $row['montant_reduction'] = $montant_reduction;
        $row['cout_points_remise'] = $cout_points_remise;
        $row['prix_final_a_payer'] = $prix_final;
        $row['points_gagnes_commande'] = $is_guest ? 0 : floor($prix_final);

        // --- 1. Gestion des Articles ---
        $stmt_items = $commande->afficherItemCommande($id_cmd);
        $row['liste_articles'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // --- 2. Gestion des Formules ---
        $stmt_formules = $commande->afficherFormulesCommande($id_cmd);
        $raw_formules = $stmt_formules->fetchAll(PDO::FETCH_ASSOC);

        $formules_structurees = [];
        if (!empty($raw_formules)) {
            foreach ($raw_formules as $ligne) {
                $id_unique = $ligne['instance_id'] ?? $ligne['id'] ?? 0;
                $nom_form = $ligne['nom_formule'] ?? $ligne['nom'] ?? 'Nom Inconnu';
                $prix_form = $ligne['prix'] ?? 0;
                $nom_item = $ligne['nom_item'] ?? $ligne['nom'] ?? '';

                if ($id_unique === 0) continue;

                if (!isset($formules_structurees[$id_unique])) {
                    $formules_structurees[$id_unique] = [
                        'nom' => $nom_form,
                        'prix' => $prix_form,
                        'items' => []
                    ];
                }
                if (!empty($nom_item)) {
                    $formules_structurees[$id_unique]['items'][] = $nom_item;
                }
            }
        }
        $row['liste_formules'] = $formules_structurees;

        $historiqueCommandes[] = $row;
    }
}

include 'views/derniere_commande.php';
?>