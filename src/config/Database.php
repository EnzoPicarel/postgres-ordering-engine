<?php

date_default_timezone_set('Europe/Paris');

// Configuration file for connecting to the database

class Database
{
    private $conn;
    // Method for obtaining connection to the database
    public function getConnection()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $db_name = getenv('DB_NAME') ?: 'restaurants';
        $username = getenv('DB_USER') ?: 'postgres';
        $password = getenv('DB_PASS') ?: 'postgres';
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$db_name}";
            $this->conn = new PDO($dsn, $username, $password);
            // Configure PDO to raise exceptions in case of errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
            die(); // Stop execution if connection fails
        }
        return $this->conn;
    }
}
?>