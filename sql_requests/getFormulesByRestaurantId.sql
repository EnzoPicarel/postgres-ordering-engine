SELECT 
    f.formule_id, 
    f.nom, 
    f.prix, 
    ci.nom as nom_categorie
FROM formules as f
INNER JOIN composer_formules as cf ON cf.formule_id = f.formule_id
INNER JOIN categories_items as ci ON ci.categorie_item_id = cf.categorie_item_id
WHERE 
    f.restaurant_id = ?  
    AND (
        
        -- Si pas de conditions => toujours dispo
        NOT EXISTS (SELECT 1 FROM avoir_conditions_formules WHERE formule_id = f.formule_id)
        
        OR
        
        -- Si conditions on vérifie le jour et l'h
        EXISTS (
            SELECT 1
            FROM avoir_conditions_formules as acf
            JOIN conditions_formules as cond ON acf.condition_formule_id = cond.condition_formule_id
            WHERE acf.formule_id = f.formule_id 
            
            -- vérif jour
            AND cond.jour_disponibilite = EXTRACT(ISODOW FROM CURRENT_DATE)
            
            -- vérif heure
            AND CURRENT_TIME BETWEEN cond.creneau_horaire_debut AND cond.creneau_horaire_fin
        )
    )
ORDER BY f.prix;