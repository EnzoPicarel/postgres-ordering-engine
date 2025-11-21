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

<div class="header-bar">
    <?php if (isset($_SESSION['client_id'])): ?>
        <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['client_nom']) ?></strong> !</p>
        
        <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">Ma dernière commande</a>
        
        <a href="logout.php" style="color: red;">Se déconnecter</a>
    <?php else: ?>
        <a href="login.php">Se connecter</a>
        <a href="create_account.php">Créer un compte</a>
    <?php endif; ?>
</div>

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