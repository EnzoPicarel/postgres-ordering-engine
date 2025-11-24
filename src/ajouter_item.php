<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Commandes.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restaurant_id']) && isset($_POST['item_id']) && isset($_SESSION['client_id'])) {
    $item_id = $_POST['item_id'];
    $restaurant_id = $_POST['restaurant_id'];
    $client_id = $_SESSION['client_id'];

    $database = new Database();
    $db = $database->getConnection();
    $commande = new Commande($db);  

    $stmt = $commande->createCommandeOrGetLastOne($client_id, $restaurant_id);
    if($stmt && isset($stmt['commande_id'])) {

        $commande_id = $stmt['commande_id'];
        $commande->addItemToCommandeContenirItem($commande_id, $item_id);
        header("Location: menu.php?id=" . $restaurant_id);
    }

} else {
    header("Location: menu_restaurant.php");
    exit();
}

?>