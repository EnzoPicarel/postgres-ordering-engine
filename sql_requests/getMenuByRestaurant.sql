SELECT item_id, nom, prix
FROM items
WHERE restaurant_id = ?
ORDER BY categorie_item_id;