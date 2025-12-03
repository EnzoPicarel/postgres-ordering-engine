<?php

require_once 'Query.php';

class Fidelite {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function ajouterPoints($client_id, $restaurant_id, $montant_commande)
    {
        $points_a_ajouter = floor($montant_commande);
        if( $points_a_ajouter <= 0 ) {
            return false;
        }

        $queryCheck = Query::loadQuery('sql_requests/checkFidelite.sql');
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(1, $client_id);
        $stmtCheck->bindParam(2, $restaurant_id);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount()>0){
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $fidelite_id = $row['fidelite_id'];
            $queryUpdate = Query::loadQuery('sql_requests/updateFidelite.sql');
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(1, $points_a_ajouter);
            $stmtUpdate->bindParam(2, $fidelite_id);
            return $stmtUpdate->execute();
        } else {
            $queryCreate = Query::loadQuery('sql_requests/createFidelite.sql');
            $stmtCreate = $this->conn->prepare($queryCreate);
            $stmtCreate->bindParam(1, $client_id);
            $stmtCreate->bindParam(2, $restaurant_id);
            $stmtCreate->bindParam(3, $points_a_ajouter);
            return $stmtCreate->execute();
        }
    }

    public function getSolde($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/checkFidelite.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row['points'];
        } else {
            return 0;
        }
    }
}
?>