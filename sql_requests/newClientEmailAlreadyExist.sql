SELECT client_id
from clients
where email = ?
LIMIT 1;