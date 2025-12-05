<?php


require_once 'Query.php';

class Ingredient {
    private $conn;

    // Database connection initialization.
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get list of ingredients for a specific item.
    public function getIngredientsByItem($item_id) {
        $query = Query::loadQuery('sql_requests/getIngredientsByItem.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$item_id);
        $stmt->execute();

        return $stmt;
    }
}

?>