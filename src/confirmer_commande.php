<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';
// On charge le modèle Fidelite pour débiter les points utilisés (récompense)
require_once './models/Fidelite.php';

if (!isset($_SESSION['client_id']) || !isset($_POST['commande_id'])) {
    header("Location: index.php");
    exit();
}

$commande_id = $_POST['commande_id'];

// Récupération sécurisée des données
$restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null;
$total = isset($_POST['total']) ? $_POST['total'] : 0;
$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : $_SESSION['client_id'];

$cout_points = isset($_POST['cout_points']) ? intval($_POST['cout_points']) : 0;

if (empty($restaurant_id)) {
    die("Erreur technique : Identifiant du restaurant manquant.");
}

// Logique Invité
$is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true;
$accorder_points = !$is_guest;

$database = new Database();
$db = $database->getConnection();
$commandeModel = new Commande($db);

// 1. Validation de la commande
// (Cette méthode ajoute aussi les points gagnés grâce au paramètre $accorder_points)
$succes = $commandeModel->confirmOrder(
    $commande_id, 
    $client_id, 
    $restaurant_id, 
    $total, 
    $accorder_points
);

if ($succes) {
    // 2. Si succès ET qu'une récompense a été utilisée, on DÉBITE les points
    if ($cout_points > 0) {
        $fidelite = new Fidelite($db);
        $fidelite->utiliserPoints($client_id, $restaurant_id, $cout_points);
    }

    header("Location: historique.php?success=1");
    exit();
} else {
    echo "Erreur lors de la confirmation de la commande.";
}
?>