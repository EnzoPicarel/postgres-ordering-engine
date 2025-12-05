SELECT item_id, nom, prix FROM items 
WHERE restaurant_id = ? AND nom ILIKE ? AND est_disponible = TRUE
ORDER BY nom LIMIT 10;