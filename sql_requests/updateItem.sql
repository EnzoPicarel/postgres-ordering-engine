UPDATE items
SET nom = ?, prix = ?, est_disponible = ?, categorie_item_id = ?
WHERE item_id = ? AND restaurant_id = ?;
