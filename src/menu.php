<?php 
/*
Résumé :
    - Validation d'entrée : Vérifie que l'ID du restaurant est présent et valide.
    - Initialisation : Connexion à la BDD et chargement des modèles Restaurant et Plat.
    - Récupération du Menu : Charge tous les plats du restaurant, en incluant un indicateur booléen (has_complements) si le plat possède des options (sauces, accompagnements).
    - Gestion du Panier Actif : 
        - Si un client est connecté, vérifie s'il a déjà une commande "en cours" (non payée) dans CE restaurant spécifique.
        - Si oui, les informations de cette commande sont récupérées pour afficher le résumé dans la sidebar et activer les boutons de suppression d'items.
    - Interactivité (Vue/JS) :
        - Les boutons "+" et "-" sont conditionnés par l'état de connexion et l'existence d'une commande.
        - Les compléments (sauces) sont chargés dynamiquement via AJAX (fetch) uniquement à la demande de l'utilisateur pour ne pas alourdir le chargement initial de la page.
        - Le panier latéral se met à jour (via rechargement de page) après chaque action d'ajout/suppression.
*/
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Plats.php';
require_once './models/Restaurants.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $restaurant_id = $_GET['id'];
}
else {
    die("Erreur :  ID du restaurant non valide ou manquant.");
}

$database = new Database();
$db = $database->getConnection();
$plat = new Plat($db);
$restaurant = new Restaurant($db);

$restaurant_info = $restaurant->getByID($restaurant_id);
$stmt_plats = $plat->getMenuByRestaurant($restaurant_id);

if (isset($_SESSION['client_id']) && is_numeric($_SESSION['client_id'])) {
    $client_id = $_SESSION['client_id'];

    $stmt_commande_by_restau = $restaurant->getCurrentCommandeFromRestaurant($client_id, $restaurant_id);

} else {
    $client_id = null;
    $stmt_commande_by_restau = null;
}


include 'views/menu_restaurant.php';

?>