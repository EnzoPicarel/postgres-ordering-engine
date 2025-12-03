<?php
// src/login.php

// Utilisation des sessions au lieu de passer l'id dans l'url, bcp plus sécurisé, permet de ne pas changer de compte en modifiant l'utilisateur dans l'url.

session_start();

/*
session_start() doit être la toute première instruction php, à mettre surtout avant tout code html
à utiliser sur chaque controleur qui ont besoin de savoir qui est connecté.
N'utiliser le session_start() qu'une fois par page (vérifier qu'il n'est pas dans les includes) sinon erreur (Notice)

*/

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
require_once './models/Clients.php';

$error_message = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (!empty($nom) && !empty($email)) {
        $database = new Database();
        $db = $database->getConnection();
        $clientModel = new Client($db);

        $result = $clientModel->getIdByLogin($nom, $email);

        if ($result) {
            $_SESSION['client_id'] = $result['client_id'];
            $_SESSION['client_nom'] = $nom;
            $_SESSION['is_admin'] = ($email === 'admin@email.fr');

            header("Location: index.php");
            exit();

        } else {
            $error_message = "Identifiants incorrects.";
        }
    }
}
include 'views/client_login.php';
?>