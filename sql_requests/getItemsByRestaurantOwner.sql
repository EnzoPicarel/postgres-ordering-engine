-- List items for a restaurant owner with availability and category
SELECT i.item_id,
       i.nom,
       i.prix,
       i.est_disponible,
       i.categorie_item_id,
       c.nom AS nom_categorie
FROM items i
JOIN categories_items c ON c.categorie_item_id = i.categorie_item_id
WHERE i.restaurant_id = ?
ORDER BY c.nom, i.nom;
