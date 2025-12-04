-- Remove a complement link between two items

DELETE FROM etre_accompagne_de
WHERE item_id1 = ?
  AND item_id2 = ?
