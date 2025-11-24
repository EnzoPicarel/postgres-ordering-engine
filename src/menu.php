<?php 

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

include 'views/menu_restaurant.php';

?>