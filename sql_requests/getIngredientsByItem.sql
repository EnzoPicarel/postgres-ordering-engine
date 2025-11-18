SELECT i.*, c.quantite_g
FROM ingredients as i
INNER JOIN composer as c ON c.ingredient_id=i.ingredient_id
INNER JOIN items as t ON t.item_id=c.item_id
WHERE t.item_id = ?
ORDER BY c.quantite_g DESC;