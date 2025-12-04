<?php

require_once 'Query.php';

class Plat {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMenuByRestaurant($restaurant_id) {
        $query = Query::loadQuery('sql_requests/getMenuByRestaurant.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id); // Remplace le premier ? par $restaurant_id dans la requête SQL
        $stmt->execute();

        return $stmt;
    }

    public function getItemsDisponibles($restaurant_id, $categorie_id) {
        $query = Query::loadQuery('sql_requests/getItemFromSpecificCategories.sql');
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id, $categorie_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItem($nom, $prix, $est_disponible, $restaurant_id, $categorie_item_id) {
        $query = Query::loadQuery('sql_requests/addItem.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $prix);
        $stmt->bindParam(3, $est_disponible);
        $stmt->bindParam(4, $restaurant_id);
        $stmt->bindParam(5, $categorie_item_id);

        $stmt->execute();

        return $stmt->fetchColumn();


    }

    public function getItemFromAllCat() {
        $query = Query::loadQuery('sql_requests/getItemFromAllCategories.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function searchItem($restaurant_id, $term) {
        $searchTerm = "%" . $term . "%";
        
        $sql = Query::loadQuery("sql_requests/searchItem.sql");
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->execute([$restaurant_id, $searchTerm]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
    
}

?>