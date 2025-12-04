<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';


if (!isset($_SESSION['client_id']) || !isset($_POST['commande_id'])) {
    header("Location: index.php");
    exit();
}

$commande_id = $_POST['commande_id'];

$restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : null;
$total = isset($_POST['total']) ? $_POST['total'] : 0;

$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : $_SESSION['client_id'];

if (empty($restaurant_id)) {
    die("Erreur technique : Identifiant du restaurant manquant.");
}

$is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true;

$accorder_points = !$is_guest;

$database = new Database();
$db = $database->getConnection();
$commandeModel = new Commande($db);

$succes = $commandeModel->confirmOrder(
    $commande_id, 
    $client_id, 
    $restaurant_id, 
    $total, 
    $accorder_points
);

if ($succes) {
    header("Location: historique.php?success=1");
    exit();
} else {
    echo "Erreur lors de la confirmation de la commande.";
}
?>