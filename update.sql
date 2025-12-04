-- =============================================================================
-- SECTION 1 : GESTION DES CLIENTS
-- =============================================================================

-- Création d'un nouveau client (utilisé lors de l'inscription)
INSERT INTO clients (nom, email, adresse) 
VALUES ('Nouveau Client', 'nouveau@email.fr', '10 Rue Example, Bordeaux');

-- Suppression d'un compte invité (clients sans compte de fidélité)
DELETE FROM clients 
WHERE client_id = 999 
  AND email LIKE 'invite_%';

-- Nettoyage des comptes invités anciens (plus de 30 jours)
DELETE FROM clients 
WHERE email LIKE 'invite_%' 
  AND client_id IN (
    SELECT c.client_id 
    FROM clients c
    LEFT JOIN commandes co ON c.client_id = co.client_id
    WHERE co.date_commande < NOW() - INTERVAL '30 days'
       OR co.commande_id IS NULL
  );


-- =============================================================================
-- SECTION 2 : GESTION DES COMMANDES
-- =============================================================================

-- Confirmation d'une commande (passage à l'état "en_livraison")
UPDATE commandes
SET etat = 'en_livraison'
WHERE commande_id = 1;

-- Marquage d'une commande comme retirée
UPDATE commandes
SET etat = 'retiree'
WHERE commande_id = 1;

-- Suppression d'une commande en cours (annulation avant validation)
DELETE FROM commandes
WHERE commande_id = 1
  AND etat = 'en_commande';

-- Ajout d'un item à une commande existante
INSERT INTO contenir_items (commande_id, item_id, quantite)
VALUES (1, 5, 2);

-- Suppression d'un item d'une commande
DELETE FROM contenir_items
WHERE commande_id_item = 123;

-- Ajout d'une formule à une commande
INSERT INTO contenir_formules (commande_id, formule_id, quantite)
VALUES (1, 3, 1);


-- =============================================================================
-- SECTION 3 : GESTION DE LA FIDÉLITÉ
-- =============================================================================

-- Création d'un compte de fidélité pour un client
INSERT INTO fidelite (client_id, restaurant_id, points) 
VALUES (1, 1, 0);

-- Mise à jour des points de fidélité après une commande
UPDATE fidelite 
SET points = points + 50
WHERE fidelite_id = 1;

-- Utilisation d'une remise (déduction de points)
UPDATE fidelite 
SET points = points - 100
WHERE fidelite_id = 1 
  AND points >= 100;


-- =============================================================================
-- SECTION 4 : GESTION DES COMMENTAIRES
-- =============================================================================

-- Ajout d'un commentaire par un client
INSERT INTO commentaires (date_commentaire, contenu, note, fidelite_id) 
VALUES (NOW(), 'Excellent restaurant, plats délicieux !', 5, 1);

-- Mise à jour d'un commentaire existant
UPDATE commentaires
SET contenu = 'Très bon restaurant, service rapide.',
    note = 4
WHERE commentaire_id = 1;

-- Suppression d'un commentaire
DELETE FROM commentaires
WHERE commentaire_id = 1;


-- =============================================================================
-- SECTION 5 : GESTION DES RESTAURANTS (Interface Restaurateur)
-- =============================================================================

-- Ajout d'un nouvel item au menu
INSERT INTO items (nom, prix, est_disponible, restaurant_id, categorie_item_id) 
VALUES ('Pizza Margherita', 12.50, TRUE, 1, 2);

-- Modification du prix d'un item
UPDATE items
SET prix = 13.00
WHERE item_id = 5;

-- Désactivation d'un item (rupture de stock)
UPDATE items
SET est_disponible = FALSE
WHERE item_id = 5;

-- Suppression définitive d'un item
DELETE FROM items
WHERE item_id = 5;

-- Création d'une nouvelle formule
INSERT INTO formules (nom, prix, restaurant_id) 
VALUES ('Menu Midi', 15.00, 1) 
RETURNING formule_id;

-- Liaison d'une catégorie d'items à une formule
INSERT INTO composer_formules (formule_id, categorie_item_id) 
VALUES (1, 2); -- Catégorie "Plat Principal"


-- =============================================================================
-- SECTION 6 : GESTION DES HORAIRES D'OUVERTURE
-- =============================================================================

-- Création d'un horaire d'ouverture
INSERT INTO horaires_ouverture (jour_semaine, heure_ouverture, heure_fermeture) 
VALUES ('Lundi', '11:00:00', '22:00:00')
RETURNING horaire_id;

-- Liaison d'un horaire à un restaurant
INSERT INTO avoir_horaires_ouverture (restaurant_id, horaire_id)
VALUES (1, 1);

-- Modification d'un horaire existant
UPDATE horaires_ouverture
SET heure_ouverture = '12:00:00',
    heure_fermeture = '23:00:00'
WHERE horaire_id = 1;

-- Suppression du lien entre un restaurant et un horaire
DELETE FROM avoir_horaires_ouverture 
WHERE restaurant_id = 1 
  AND horaire_id = 1;

-- Suppression d'un horaire
DELETE FROM horaires_ouverture
WHERE horaire_id = 1;


-- =============================================================================
-- SECTION 7 : GESTION DES INGRÉDIENTS ET COMPOSITIONS
-- =============================================================================

-- Ajout d'un nouvel ingrédient
INSERT INTO ingredients (nom, quantite_proteine, quantite_energie)
VALUES ('Tomate', 0.9, 18);

-- Ajout d'un ingrédient à la composition d'un item
INSERT INTO composer_items (item_id, ingredient_id, quantite_gramme)
VALUES (5, 10, 150.0);

-- Mise à jour de la quantité d'un ingrédient dans un item
UPDATE composer_items
SET quantite_gramme = 200.0
WHERE item_id = 5 
  AND ingredient_id = 10;

-- Suppression d'un ingrédient d'un item
DELETE FROM composer_items
WHERE item_id = 5 
  AND ingredient_id = 10;


-- =============================================================================
-- SECTION 8 : GESTION DES COMPLÉMENTS (Sauces, Accompagnements)
-- =============================================================================

-- Ajout d'un complément disponible pour un item
INSERT INTO etre_accompagne_de (item_id1, item_id2)
VALUES (2, 9); -- Coq au vin peut avoir Sauce Piquante comme complément

-- Suppression d'une relation de complément
DELETE FROM etre_accompagne_de
WHERE item_id1 = 2 
  AND item_id2 = 9;

-- Lister tous les compléments disponibles pour un item spécifique
-- (Utilisé par l'endpoint AJAX getComplements.php)
SELECT items.item_id, items.nom, items.prix
FROM items
JOIN etre_accompagne_de ON items.item_id = etre_accompagne_de.item_id2
WHERE etre_accompagne_de.item_id1 = 2;