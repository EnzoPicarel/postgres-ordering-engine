<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();


require_once './config/Database.php';
require_once './models/Commandes.php';

if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];
}
else {
    die("Erreur : Aucun ID client fourni.");
}

$database = new Database();
$db = $database->getConnection();

$commande = new Commande($db);

$stmt = $commande->getCurrentCommande($client_id);

include 'views/derniere_commande.php';
?>