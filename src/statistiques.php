<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit();
}

require_once './config/Database.php';
require_once './models/Query.php';

$database = new Database();
$db = $database->getConnection();

function fetchAll($db, $sqlPath, $params = [])
{
    $sql = Query::loadQuery($sqlPath);
    $stmt = $db->prepare($sql);
    foreach ($params as $idx => $val) {
        $stmt->bindValue($idx + 1, $val);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 1. Clients avec compte: nombre de commandes et montant total
$clientsStats = fetchAll($db, 'sql_requests/admin_clients_stats.sql');

// 2. Restaurants par coût moyen des plats principaux (desc)
$restoAvgMains = fetchAll($db, 'sql_requests/admin_restaurants_avg_main_cost.sql');

// 3. Restaurants de Bordeaux avec nb de commandes des 30 derniers jours
$restoBordeauxRecent = fetchAll($db, 'sql_requests/admin_bordeaux_recent_orders.sql');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #f7f7f7;
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px;
        }

        h1 {
            margin: 0 0 20px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #fafafa;
            font-weight: 600;
        }

        .back {
            display: inline-block;
            margin-bottom: 16px;
            text-decoration: none;
            background: #2c3e50;
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="back">← Retour</a>
        <h1>📊 Statistiques</h1>

        <div class="card">
            <h2>Clients (avec compte)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Commandes</th>
                        <th>Montant total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientsStats as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= (int) ($row['nb_commandes'] ?? 0) ?></td>
                            <td><?= number_format((float) ($row['montant_total'] ?? 0), 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Restaurants par coût moyen des plats principaux</h2>
            <table>
                <thead>
                    <tr>
                        <th>Restaurant</th>
                        <th>Adresse</th>
                        <th>Coût moyen (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restoAvgMains as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['adresse']) ?></td>
                            <td><?= number_format((float) ($row['cout_moyen'] ?? 0), 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Restaurants de Bordeaux (30 derniers jours)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Restaurant</th>
                        <th>Commandes (30j)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restoBordeauxRecent as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= (int) ($row['nb_commandes_30j'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>