<?php

require_once 'Query.php';

class Complements
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getComplements($item_id)
    {
        $query = Query::loadQuery('sql_requests/getComplements.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id);
        $stmt->execute();
        return $stmt;
    }

    public function addComplement($item_id1, $item_id2)
    {
        $query = Query::loadQuery('sql_requests/addComplement.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id1);
        $stmt->bindParam(2, $item_id2);
        return $stmt->execute();
    }

    public function deleteComplement($item_id1, $item_id2)
    {
        $query = Query::loadQuery('sql_requests/deleteComplement.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id1);
        $stmt->bindParam(2, $item_id2);
        return $stmt->execute();
    }
}

?>