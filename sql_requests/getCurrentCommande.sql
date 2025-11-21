SELECT c.*
FROM commandes as c
WHERE client_id = ?
ORDER BY c.date_commande DESC
LIMIT 1;