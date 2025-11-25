<?php

require_once 'Query.php';

class Commande {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCurrentCommande($client_id) {
        $query = Query::loadQuery('sql_requests/getCurrentCommande.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);

        $stmt->execute();
        
        return $stmt;
    }

    public function suppressCurrentCommande($commande_id) {
        $query = Query::loadQuery('sql_requests/suppressCurrentCommande.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $commande_id);

        $stmt->execute();
        
        return $stmt;
    }
    
    public function afficherItemCommande($commande_id) {
        $query = Query::loadQuery('sql_requests/getAllItemCommande.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        
        return $stmt;
    }

    private function getLastCommandeFromRestaurant($client_id, $restaurant_id) {
        $query1 = Query::loadQuery('sql_requests/getCurrentCommandeFromRestaurant.sql');

        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(1, $client_id);
        $stmt1->bindParam(2, $restaurant_id);
        $stmt1->execute();

        return $stmt1->fetch(PDO::FETCH_ASSOC);
    }

    private function createCommande($client_id, $restaurant_id) {
        $query2 = Query::loadQuery('sql_requests/createNewCommande.sql');

        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(1, $client_id);
        $stmt2->bindParam(2, $restaurant_id);
        $stmt2->execute();
    }

    public function createCommandeOrGetLastOne($client_id, $restaurant_id) {
        
        $row = Commande::getLastCommandeFromRestaurant($client_id, $restaurant_id);

        if ($row) {
            return $row; 
        }

        // Si on arrive ici c'est que la commande n'existait pas 

        Commande::createCommande($client_id, $restaurant_id);
        
        return Commande::getLastCommandeFromRestaurant($client_id, $restaurant_id);
    }

    public function addItemToCommandeContenirItem($commande_id, $item_id) {
        $query0 = Query::loadQuery('sql_requests/getItemInContenirItem.sql');

        $stmt0 = $this->conn->prepare($query0);
        $stmt0->bindParam(1, $commande_id);
        $stmt0->bindParam(2, $item_id);
        
        if (!$stmt0->execute()) return false;

        if ($stmt0->fetch() === false) {
            
            $query1 = Query::loadQuery('sql_requests/addItemToCommandeContenirItem.sql');

            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(1, $commande_id);
            $stmt1->bindParam(2, $item_id);

            return $stmt1->execute();

        } else {
            
            $query2 = Query::loadQuery('sql_requests/updateQuantityContenirItem.sql');

            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(1, $commande_id);
            $stmt2->bindParam(2, $item_id);
        
            return $stmt2->execute();    

        }
    }
}
?>