SELECT item_id, nom, prix FROM items 
WHERE restaurant_id = ? AND nom ILIKE ? 
ORDER BY nom LIMIT 10;