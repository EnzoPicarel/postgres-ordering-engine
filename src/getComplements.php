<?php

session_start();

require 'config/Database.php';
require 'models/Query.php';
require 'models/Complements.php';

header('Content-Type: application/json');

if (!isset($_GET['item_id'])) {
    echo json_encode([]);
    exit;
}

$item_id = $_GET['item_id'];

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$complements = new Complements($conn);
$stmt = $complements->getComplements($item_id);

$result = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $result[] = [
        'item_id' => $row['item_id'],
        'nom' => $row['nom'],
        'prix' => $row['prix']
    ];
}

echo json_encode($result);

?>