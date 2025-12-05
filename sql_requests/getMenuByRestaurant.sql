-- Get complete menu for a restaurant with item properties (vegetarian, gluten-free, etc.)

SELECT i.item_id, i.nom, i.prix, c.nom AS nom_categorie, p.nom AS nom_propriete, CASE WHEN EXISTS (SELECT 1 FROM etre_accompagne_de WHERE item_id1 = i.item_id) THEN 1 ELSE 0 END as has_complements
FROM items AS i
INNER JOIN categories_items AS c ON c.categorie_item_id = i.categorie_item_id
LEFT JOIN avoir_proprietes_items AS api ON api.item_id = i.item_id
LEFT JOIN proprietes_items AS p ON p.propriete_items_id = api.propriete_items_id 
WHERE restaurant_id = ?
	AND i.est_disponible = TRUE
	AND NOT EXISTS (
				SELECT 1 FROM etre_accompagne_de ea
				WHERE ea.item_id2 = i.item_id
	)
ORDER BY i.categorie_item_id