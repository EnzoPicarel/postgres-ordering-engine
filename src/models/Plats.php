<?php

class Plat {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMenuByRestaurant($restaurant_id) {
        $path = __DIR__ . '/../../sql_requests/getMenuByRestaurant.sql';

        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : Impossible de lire le fichier de requête SQL getAllMenuByRestaurant.sql.");
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id); // Remplace le premier ? par $restaurant_id dans la requête SQL
        $stmt->execute();

        return $stmt;
    }
}

?>