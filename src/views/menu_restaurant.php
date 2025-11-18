<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu du Restaurant</title>
    <style>
        .plat-item { border-bottom: 1px solid #eee; padding: 10px; }
        .plat-item h4 { margin: 0; }
        .plat-item p { margin: 5px 0 0; font-style: italic; }
        .plat-item .prix { font-weight: bold; float: right; }
    </style>
</head>
<body>
    <?php
        if ($restaurant_info) {
            echo "<h1> Menu de " . htmlspecialchars($restaurant_info['nom']) ."</h1>"; //htmlspecialchars => fct de sécurité php 
                                                                                // pour éviter les pb avec les caractères spéciaux
        }
        else {
            echo "<h1> Restaurant non trouvé </h1>";
        }
    ?>
    <a href="index.php">Retour à la liste des restaurants</a>

    <div class="menu-container">
        <?php
        if ($stmt_plats->rowCount() > 0) {
            while ($row = $stmt_plats->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                echo "<div class='plat-item'>";
                    echo "<span class='prix'>{$prix} €</span>";
                    echo "<h4><a href='composition.php?item_id={$item_id}'>{$nom}</a></h4>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucun plat n'a été trouvé pour ce restaurant.</p>";
        }
        ?>
    </div>

</body>
</html>