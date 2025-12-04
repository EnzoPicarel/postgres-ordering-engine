-- Get available complements (sauces, toppings, sides) for an item

SELECT i.item_id, i.nom, i.prix
FROM items i
JOIN etre_accompagne_de ea ON i.item_id = ea.item_id2
WHERE ea.item_id1 = ?
ORDER BY i.nom
