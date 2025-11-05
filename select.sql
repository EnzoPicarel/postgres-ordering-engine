--REQUETE CONSULTATION

--Liste des restaurants de chaque catégorie.

SELECT CR.*, R.* from categorieResto as CR
    JOIN lien_categorie_restaurant as L1 on L1.categorie_restaurant_id = CR.categorie_restaurant_id  
    JOIN restaurant as R on L1.restaurant_id = R.restaurant_id
    ORDER BY CR.categorie_restaurant_id;


--Liste des restaurants selon la disponibilité de plats de chaque catégorie.

SELECT CI.*,I.* from categorieItems as CI
    JOIN specifier as S on S.categorie_id = CI.categorie_id
    JOIN Items as I on I.items_id = S.items_id
    JOIN Restaurant as R on R.restaurant_id = I.restaurant_id
    WHERE I.disponibilite = TRUE;


--La liste des commandes passées par des clients sans compte.

SELECT C.* from Clients as Cl 
    JOIN Commandes as Co on Co.client_id = Cl.client_id
    WHERE Cl.client_id NOT IN (

            SELECT F.client_id from Fidelisation as F
    );


--REQUETE STATISTIQUE 

--La liste des clients avec un compte, avec le nombre de commandes qu’ils ont passé, et le montant total.

SELECT Cl.client_id, Cl.nom, count(Co.commande_id), SUM(Co.prix_total_remisé) from Client as Cl 
    JOIN Fidelisation as F on F.client_id = Cl.client_id
    JOIN Commandes as Co on Co.client_id = Cl.client_id
    GROUP BY Cl.client_id, Cl.nom;


--La liste des restaurants classés par ordre décroissant du coût moyen des plats principaux.

SELECT R.nom, R.restaurant_id, AVG(I.prix_item) as moyenne_prix_plat_principale from Restaurant
    JOIN Item as I on I.restaurant_id = R.restaurant_id
    JOIN categorieItems as CI on CI.categorie_id = I.categorie_id
    WHERE CI.nom_categorie = "Principal"
    GROUP BY R.nom, R.restaurant_id
    ORDER BY DESC moyenne_prix_plat_principale;



--La liste des restaurant de Bordeaux, avec le nombre de commandes passées durant les 30 derniers jours.

SELECT R.restaurant_id, R.nom, COUNT(Co.commande_id) AS nb_commandes 
    FROM  restaurant AS R
    JOIN Commande AS Co ON R.restaurant_id = Co.restaurant_id
    WHERE UPPER(R.adresse) LIKE UPPER('%Bordeaux%')
        AND Co.date_commande >= (NOW() - INTERVAL '30 days')
    GROUP BY R.restaurant_id, R.nom;


--Chaque restaurant doit pouvoir consulter les statistiques de commandes de chaque plat par mois, pour l’année écoulée.

SELECT R.restaurant_id, R.nom, MONTHNAME(Co.date_commande) ,count(A.items_id) as Nb_commande, I.items_id, I.nom from Restaurant as R 
    NATURAL JOIN Item as I
    JOIN appartenir as A on A.items_id = I.items_id
    JOIN Commande as Co on Co.commande_id = A.commande_id
    WHERE Co.date_commande >= (NOW() - INTERVAL '1 year')
    GROUP BY R.restaurant_id, R.nom, MONTHNAME(Co.date_commande),I.items_id, I.nom