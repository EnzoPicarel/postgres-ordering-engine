<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu du Restaurant</title>
    <style>
        /* --- Styles existants --- */
        .categorie-titre {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 5px solid #2c3e50;
            margin-top: 20px;
            color: #2c3e50;
        }
        
        /* Modification pour utiliser Flexbox : alignement horizontal */
        .plat-item { 
            border-bottom: 1px solid #eee; 
            padding: 15px 0; 
            display: flex;
            justify-content: space-between; /* Espace entre infos (gauche) et actions (droite) */
            align-items: center;
        }

        .plat-info {
            flex-grow: 1; /* Prend toute la place disponible */
        }

        .plat-info h4 { margin: 0 0 5px 0; }
        .plat-info p { margin: 0; display: inline-block; font-weight: bold; }
        
        .plat-actions {
            text-align: right;
            min-width: 150px; /* S'assure que la droite a assez de place */
        }

        .prix { 
            font-weight: bold; 
            color: #e67e22; 
            font-size: 1.2em;
            display: block; /* Le prix au-dessus du bouton */
            margin-bottom: 5px;
        }
        
        .badge-prop {
            background-color: #e1f5fe;
            color: #0277bd;
            font-size: 0.8em;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
            vertical-align: middle;
            font-weight: normal;
        }

        .lien-ingredients {
            font-size: 0.9em;
            color: #7f8c8d;
            text-decoration: none;
            margin-left: 10px;
        }
        .lien-ingredients:hover { text-decoration: underline; }

        /* --- NOUVEAU STYLE POUR LE BOUTON AJOUTER --- */
        .btn-add {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-add:hover { background-color: #27ae60; }
        .btn-add:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .not-connected-msg { font-size: 0.7em; color: red; display: block;}

    </style>
</head>
<body>
    <?php
        // Récupération de l'ID client pour savoir si on active les boutons
        if (isset($_SESSION['client_id'])) {
            $client_id = $_SESSION['client_id'];
        } else {
            $client_id = null; 
        }
    ?>

    <?php
        if ($restaurant_info) {
            echo "<h1> Menu de " . htmlspecialchars($restaurant_info['nom']) ."</h1>"; 
        } else {
            echo "<h1> Restaurant non trouvé </h1>";
        }
    ?>
    <a href="index.php">Retour à la liste des restaurants</a>

    <div class="menu-container">
        <?php
        if ($stmt_plats->rowCount() > 0) {
            
            // --- 1. TRI DES DONNÉES (Identique à avant) ---
            $menu_organise = [];

            while ($row = $stmt_plats->fetch(PDO::FETCH_ASSOC)) {
                $cat = $row['nom_categorie'];
                $id_plat = $row['item_id'];
                
                if (!isset($menu_organise[$cat])) {
                    $menu_organise[$cat] = [];
                }

                if (!isset($menu_organise[$cat][$id_plat])) {
                    $menu_organise[$cat][$id_plat] = [
                        'nom' => $row['nom'],
                        'prix' => $row['prix'],
                        'item_id' => $row['item_id'],
                        'proprietes' => [] 
                    ];
                }

                if (!empty($row['nom_propriete'])) {
                    $menu_organise[$cat][$id_plat]['proprietes'][] = $row['nom_propriete'];
                }
            }

            // --- 2. AFFICHAGE ---
            foreach ($menu_organise as $nom_categorie => $les_plats) {
                echo "<h2 class='categorie-titre'>" . htmlspecialchars($nom_categorie) . "</h2>";

                foreach ($les_plats as $plat) {
                    echo "<div class='plat-item'>";
                        
                        // PARTIE GAUCHE : INFOS
                        echo "<div class='plat-info'>";
                            echo "<h4>";
                                echo htmlspecialchars($plat['nom']);
                                
                                // Affichage des propriétés
                                if (!empty($plat['proprietes'])) {
                                    foreach ($plat['proprietes'] as $prop) {
                                        echo "<span class='badge-prop'>" . htmlspecialchars($prop) . "</span>";
                                    }
                                }
                            echo "</h4>";
                            echo "<a class='lien-ingredients' href='composition.php?item_id={$plat['item_id']}'>Voir ingrédients</a>";
                        echo "</div>";

                        // PARTIE DROITE : PRIX + BOUTON
                        echo "<div class='plat-actions'>";
                            echo "<span class='prix'>{$plat['prix']} €</span>";

                            // --- LE FORMULAIRE D'AJOUT ---
                            // On suppose que votre contrôleur s'appelle 'ajouter_item.php'
                            echo "<form action='ajouter_item.php' method='POST'>";
                                
                                // On envoie l'ID de l'item
                                echo "<input type='hidden' name='item_id' value='" . $plat['item_id'] . "'>";
                                
                                // On envoie l'ID du restaurant (il faut que cette variable $restaurant_id existe dans le contrôleur menu.php)
                                // Si $restaurant_info['id'] est dispo, utilisez-le, sinon $restaurant_id
                                echo "<input type='hidden' name='restaurant_id' value='" . (isset($restaurant_id) ? $restaurant_id : '') . "'>";

                                if ($client_id) {
                                    echo "<button type='submit' class='btn-add'>Ajouter +</button>";
                                } else {
                                    echo "<button type='button' class='btn-add' disabled>Ajouter +</button>";
                                    echo "<span class='not-connected-msg'>(Connectez-vous)</span>";
                                }

                            echo "</form>";
                            // -----------------------------

                        echo "</div>"; // fin plat-actions

                    echo "</div>"; // fin plat-item
                }
            }

        } else {
            echo "<p>Aucun plat n'a été trouvé pour ce restaurant.</p>";
        }
        ?>
    </div>

</body>
</html>