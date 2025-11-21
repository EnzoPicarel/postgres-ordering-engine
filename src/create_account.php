<?php

session_start(); 

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Clients.php';


$error_message = NULL;
$error_message_email = NULL;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';

    if (!empty($nom) && !empty($email) && !empty($adresse)) {
        $database = new Database();
        $db = $database->getConnection();
        $client = new Client($db);

        if ($client->newClientEmailAlreadyExist($email) == false) {
            $client->createClient($nom, $email, $adresse);
            $result = $client->getIdByLogin($nom, $email);

            if ($result) {
                $_SESSION['client_id'] = $result['client_id'];
                $_SESSION['client_nom'] = $nom;

                header("Location: index.php");
                exit(); 
            }
            else {
                $error_message = "Erreur dans la création d'un compte";
            }
        }
        else {
            $error_message_email = "Cet email est déjà utilisé pour un autre compte.";
        }
    }
}

include 'views/account_creation.php';


?>