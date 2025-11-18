<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Inclure les fichiers nécessaires
require_once './config/Database.php';
require_once './models/Restaurants.php';

// 2. Initialiser la connexion à la BDD
$database = new Database();
$db = $database->getConnection();

// 3. Créer une instance du Modèle Restaurant
$restaurant = new Restaurant($db);

// 4. Appeler la méthode pour obtenir les données
$stmt = $restaurant->getAllRestaurants(); // $stmt est le résultat de la requête

// 5. Inclure la Vue pour afficher les données
// Note : Le fichier liste_restaurants.php utilise la variable $stmt
include 'views/liste_restaurants.php';
?>

