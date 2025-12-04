<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';

// 1. Vérification session
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id']) && isset($_POST['restaurant_id'])) {
    
    $client_id = $_SESSION['client_id'];
    $item_id = $_POST['item_id'];
    $restaurant_id = $_POST['restaurant_id'];

    $database = new Database();
    $db = $database->getConnection();
    $commande = new Commande($db);

    try {
        // 2. Tentative d'ajout
        $commande->ajouterAuPanier($client_id, $restaurant_id, $item_id);
        
        // Succès
        header("Location: menu.php?id=" . $restaurant_id . "&success=item_added");
        exit();

    } catch (PDOException $e) {
        
        // --- GESTION DE L'ERREUR "CLIENT INCONNU" (23503) ---
        if ($e->getCode() == '23503') {
            // Le client ID en session n'existe plus en BDD (suite à un reset)
            
            // 1. On détruit la session pourrie
            session_unset();
            session_destroy();
            session_start(); // On redémarre une session propre
            
            // 2. Message d'erreur explicite
            $error_msg = "Votre session a expiré ou le compte n'existe plus. Veuillez vous reconnecter.";
            
            // 3. Si c'était un invité, on peut proposer de recréer un accès
            echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'>";
            echo "<h1 style='color:#e74c3c;'>⚠️ Problème de session</h1>";
            echo "<p>$error_msg</p>";
            echo "<p>L'identifiant client <strong>$client_id</strong> est introuvable.</p>";
            echo "<a href='guest_login.php' style='background:#27ae60; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Relancer le mode Invité</a>";
            echo "<br><br>";
            echo "<a href='index.php' style='color:#7f8c8d;'>Retour à l'accueil</a>";
            echo "</div>";
            exit();
        }
        
        // Autres erreurs (ex: mélanger deux restos)
        elseif ($e->getCode() == 'P0001' || $e->getCode() == '20002') { 
            header("Location: menu.php?id=$restaurant_id&error=mixed_cart");
            exit();
        } else {
            die("Erreur SQL technique : " . $e->getMessage());
        }
    }

} else {
    header("Location: index.php");
    exit();
}
?>