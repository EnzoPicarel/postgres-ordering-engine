<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Dernière Commande</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f9; }
        .commande-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            border-left: 5px solid #2c3e50;
        }
        .commande-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .commande-header h2 { margin: 0; color: #333; }
        .commande-info p { margin: 8px 0; font-size: 1.1em; }
        .label { font-weight: bold; color: #555; }
        .prix-total {
            font-size: 1.4em;
            font-weight: bold;
            color: #27ae60;
            margin-top: 15px;
            text-align: right;
        }
        .alert { color: #c0392b; font-weight: bold; }
        .btn-retour { display: inline-block; margin-top: 20px; text-decoration: none; color: #333;}
    </style>
</head>
<body>

    <a href="index.php" class="btn-retour">← Retour</a>

    <?php
    // On vérifie si $commande_info contient des données (n'est pas false)
    if ($commande_info) {
        
        // Formatage de la date de commande
        $dateCmd = new DateTime($commande_info['date_commande']);
        $dateAffichee = $dateCmd->format('d/m/Y à H:i');

        // Gestion du prix (remplacer le point par une virgule)
        $prixAffiche = number_format($commande_info['prix_total_remise'], 2, ',', ' ');

        echo "<div class='commande-card'>";
            echo "<div class='commande-header'>";
                echo "<h2>Commande #" . htmlspecialchars($commande_info['commande_id']) . "</h2>";
            echo "</div>";

            echo "<div class='commande-info'>";
                echo "<p><span class='label'>Date de commande :</span> " . $dateAffichee . "</p>";

                // Affichage conditionnel de l'heure de retrait
                if (!empty($commande_info['heure_retrait'])) {
                    $retraitCmd = new DateTime($commande_info['heure_retrait']);
                    $heureRetrait = $retraitCmd->format('H:i');
                    echo "<p><span class='label'>Heure de retrait prévue :</span> " . $heureRetrait . "</p>";
                } else {
                    echo "<p><span class='label'>Retrait :</span> <span style='color:#777; font-style:italic;'>Non spécifié</span></p>";
                }

                echo "<div class='prix-total'>" . $prixAffiche . " €</div>";
            echo "</div>";
        echo "</div>";

    } else {
        echo "<div class='commande-card'>";
        echo "<p class='alert'>Aucune commande trouvée pour ce client.</p>";
        echo "</div>";
    }
    ?>

</body>
</html>