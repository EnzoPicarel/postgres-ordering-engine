<?php


require_once 'Query.php';

class Ingredient {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getIngredientsByItem($item_id) {
        $query = Query::loadQuery('sql_requests/getIngredientsByItem.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$item_id);
        $stmt->execute();

        return $stmt;
    }
}

?>