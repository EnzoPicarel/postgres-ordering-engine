<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Ingredients.php';

if (isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
}
else {
    die("Erreur : ID de l'item non valide ou manquant.");
}

$database = new Database();
$db = $database->getConnection();

$ingredient = new Ingredient($db);

$stmt = $ingredient->getIngredientsByItem($item_id);

include 'views/liste_ingredients.php';

?>