<?php
// src/api_search_items.php

// Désactiver l'affichage des erreurs HTML pour ne pas casser le JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

// Sécurité : Seul le restaurateur connecté peut chercher
if (!isset($_SESSION['restaurant_id'])) {
    echo json_encode([]);
    exit();
}

try {
    // Chemins absolus pour éviter les erreurs "No such file"
    require_once __DIR__ . '/config/Database.php';
    require_once __DIR__ . '/models/Plats.php';

    $db = (new Database())->getConnection();
    
    // --- CORRECTION ICI : On passe $db au constructeur ---
    $item = new Plat($db);
    // ----------------------------------------------------

    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    $resto_id = $_SESSION['restaurant_id'];

    if (strlen($term) >= 2) {
        // Appel de la méthode du modèle
        $results = $item->searchItem($resto_id, $term); // Pas besoin de "%" ici si le modèle le gère, sinon ajouter "%$term%"
        echo json_encode($results);
    } else {
        echo json_encode([]);
    }

} catch (Exception $e) {
    // En cas de crash, on renvoie un JSON vide ou une erreur logguée
    error_log($e->getMessage()); // Écrit dans les logs serveur
    echo json_encode([]); 
}
?>