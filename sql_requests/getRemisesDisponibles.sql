SELECT 
    r.remise_id, 
    r.type_remise, 
    r.description, 
    r.seuil_points,
    pr.pourcentage,
    io.item_id AS item_offert_id,
    i.nom AS nom_item_offert,
    i.prix AS prix_item_offert
FROM remises r
LEFT JOIN pourcentage_remise pr ON r.remise_id = pr.remise_id
LEFT JOIN item_offert io ON r.remise_id = io.remise_id
LEFT JOIN items i ON io.item_id = i.item_id
WHERE r.restaurant_id = :restaurant_id 
AND r.seuil_points <= :solde_points
ORDER BY r.seuil_points ASC;