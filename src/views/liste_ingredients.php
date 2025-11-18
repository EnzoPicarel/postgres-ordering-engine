<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails Nutritionnels</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        .nutrition-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .ingredient-list {
            font-style: italic;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .nutrition-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .nutrition-table th, .nutrition-table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
            text-align: left;
        }
        .nutrition-table th {
            background-color: #f8f9fa;
            width: 60%;
        }
        .valeur {
            font-weight: bold;
            color: #2c3e50;
        }
        .retour-btn { text-decoration: none; color: #333; font-weight: bold; display: inline-block; margin-bottom: 20px;}
    </style>
</head>
<body>

    <a href="javascript:history.back()" class="retour-btn">← Retour</a>

    <div class="nutrition-card">
        <h2>Composition & Nutrition</h2>

        <?php
        if (isset($stmt) && $stmt->rowCount() > 0) {
            
            $liste_ingredients = [];
            $masse_totale_plat = 0;
            $total_kcal_absolu = 0;
            $total_proteines_absolu = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nom = $row['nom'];
                $qte = $row['quantite_g']; 
                $kcal_100g_ing = $row['kcal_pour_100g'];
                $prot_100g_ing = $row['proteines_pour_100g'];

                $liste_ingredients[] = htmlspecialchars($nom);

                $masse_totale_plat += $qte;

                $total_kcal_absolu += ($kcal_100g_ing / 100) * $qte;
                $total_proteines_absolu += ($prot_100g_ing / 100) * $qte;
            }

            if ($masse_totale_plat > 0) {
                $kcal_final_100g = ($total_kcal_absolu / $masse_totale_plat) * 100; 
                $prot_final_100g = ($total_proteines_absolu / $masse_totale_plat) * 100;
            } else {
                $kcal_final_100g = 0;
                $prot_final_100g = 0;
            }

            echo "<h3>Ingrédients</h3>";
            echo "<p class='ingredient-list'>";
            
            echo implode(', ', $liste_ingredients) . "."; //rassemble le tableau en une chaîne séparée par des virgules
            echo "</p>";

            echo "<h3>Valeurs nutritionnelles moyennes</h3>";
            echo "<p><small>Pour 100 g de produit fini</small></p>";
            
            echo "<table class='nutrition-table'>";
                echo "<tr>";
                    echo "<th>Énergie</th>";
                   
                    echo "<td class='valeur'>" . number_format($kcal_final_100g, 0) . " kcal</td>";  // permet d'arrondir proprement 
                echo "</tr>";
                echo "<tr>";
                    echo "<th>Protéines</th>";
                    echo "<td class='valeur'>" . number_format($prot_final_100g, 1) . " g</td>";
                echo "</tr>";
            echo "</table>";

        } else {
            echo "<p>Aucune information disponible pour ce plat.</p>";
        }
        ?>
    </div>

</body>
</html>