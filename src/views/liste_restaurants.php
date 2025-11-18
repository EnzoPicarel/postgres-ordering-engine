<style>
    .restaurant-card {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    .restaurant-card a {
        font-weight: bold;
        text-decoration: none;
        color: blue;
    }
</style>

<h2>Liste des Restaurants Disponibles 🍽️</h2>

<?php
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        echo "<div class='restaurant-card'>";
            echo "<a href='menu.php?id={$restaurant_id}'>{$nom}</a>";
            echo "<p>Adresse : {$adresse}</p>";
        echo "</div>";
    }
} else {
    echo "<p>Aucun restaurant n'a été trouvé.</p>";
}
?>