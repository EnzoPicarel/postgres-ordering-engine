<?php
require_once 'config/Database.php';

class Restaurant {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRestaurants() {

        $path = __DIR__ . '/../../sql_requests/getAllRestaurants.sql';
        
        $query = file_get_contents($path);

        if ($query === false) {
            die("Erreur : Impossible de lire le fichier de requête SQL getAllRestaurants.sql.");
        }
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt; 
    }

    public function getByID($restaurant_id) {
        $path = __DIR__ . '/../../sql_requests/getRestaurantById.sql';

        $query = file_get_contents($path);

        if ($query === false) {
            die("Erreur : impossible de lire le fichier de requête SQL getRestaurantById.sql.");
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$restaurant_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}
?>