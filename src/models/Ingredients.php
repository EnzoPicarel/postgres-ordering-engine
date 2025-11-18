<?php

class Ingredient {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getIngredientsByItem($item_id) {
        $path = __DIR__ . '/../../sql_requests/getIngredientsByItem.sql';

        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : impossible de lire le fichier de requête SQL getIngredientsByItem.sql.");
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$item_id);
        $stmt->execute();

        return $stmt;
    }
}

?>