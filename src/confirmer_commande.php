<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';
require_once './models/Fidelite.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id']) && isset($_POST['restaurant_id']) && isset($_POST['client_id']) && isset($_POST['total'])) {
    $commande_id = $_POST['commande_id'];
    $database = new Database();
    $db = $database->getConnection();

    $commande = new Commande($db);
    $fidelite = new Fidelite($db);

    if ($commande->confirmOrder($commande_id)) {
        // Gérer la fidélité
        $restaurant_id = $_POST['restaurant_id'];
        $client_id = $_POST['client_id'];
        $total = $_POST['total'];
        $fidelite->ajouterPoints($client_id, $restaurant_id, $total);
        header("Location: commande.php");
        exit();
    } else {
        echo "Erreur lors de la confirmation de la commande.";
    }
}

?>