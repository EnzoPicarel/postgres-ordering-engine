SELECT f.formule_id,
       f.nom,
       f.prix
FROM formules f
WHERE f.restaurant_id = ?
ORDER BY f.nom;
