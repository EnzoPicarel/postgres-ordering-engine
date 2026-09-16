<div align="center">
  <h3 align="center">Postgres Ordering Engine</h3>

  <p align="center">
    A complete restaurant ordering platform featuring <strong>PostGIS geolocation</strong>, <strong>ACID-compliant order processing</strong>, and <strong>Dockerized architecture</strong>.
    <br />
    <a href="#-getting-started"><strong>Quick Start »</strong></a>
  </p>
  
  <p align="center">
    <img alt="PHP" src="https://img.shields.io/badge/PHP-777BB4?style=flat&amp;logo=php&amp;logoColor=white">
    <img alt="PostgreSQL" src="https://img.shields.io/badge/PostgreSQL-4169E1?style=flat&amp;logo=postgresql&amp;logoColor=white">
    <img alt="PostGIS" src="https://img.shields.io/badge/PostGIS-008A45?style=flat&amp;logoColor=white">
    <img alt="Docker" src="https://img.shields.io/badge/Docker-2496ED?style=flat&amp;logo=docker&amp;logoColor=white">
  </p>
</div>

## 🔍 About The Project
This project simulates a centralized food delivery ecosystem connecting customers to multiple restaurants. 

The project is built to handle **complex business rules**, such as verifying delivery zones via spatial queries, enforcing strict menu composition constraints (Formulas), and managing atomic transactions for orders and loyalty points.

*Built as a Semester 7 project at ENSEIRB-MATMECA.*

### 🛠 Built With
* **Backend:** PHP 8.1 (PDO)
* **Database:** PostgreSQL 13 + **PostGIS Extension**
* **Frontend:** HTML5 / CSS3 / JavaScript
* **Infrastructure:** Docker & Docker Compose
* **Server:** Apache

## 📐 Architecture
### Technical Highlights
* **Geospatial Processing (PostGIS):** Calculates distances and delivery zones using `ST_Distance` and `ST_DWithin` spatial queries.
* **ACID Transactions:** Ensures atomicity for orders and inventory updates to prevent data inconsistency.
* **Database Logic:** Uses PL/pgSQL triggers and stored procedures to enforce business rules directly at the data level.

### File Organization
```text
├── docker-compose.yml      # Docker orchestration (web + db services)
├── src/                    # PHP application code
│   ├── models/             # Business logic & DB models
│   └── views/              # HTML presentation layer
├── drop.sql                # Database cleanup script
├── create.sql              # Database schema (tables, functions, triggers)
└── insert.sql              # Sample data (restaurants, items, customers)
```

## 🚀 Getting Started

### Prerequisites
* **Docker Engine** (20.10+)
* **Docker Compose** (v2.0+)

### Installation & Build
1. **Clone and Setup Environment**
   ```bash
   # Clone the repo
   git clone https://github.com/EnzoPicarel/postgres-ordering-engine.git
   
   #Configure environment
   cp .env.example .env
   ```

2. Build and start services (Database + Web Server)
   ```bash
   docker-compose up --build
   ```

**What happens:**
1.  **Database** (PostgreSQL 13 + PostGIS) starts on port `5432`.
2.  **Auto-Seeding:** Runs `drop.sql` → `create.sql` → `insert.sql`.
3.  **Web Server** (PHP 8.1 + Apache) starts on port `8080`.

**Access the app:** [http://localhost:8080/src/index.php](http://localhost:8080/src/index.php)

### Stopping the App
```bash
docker-compose down       # Stop containers
docker-compose down -v    # Stop and wipe database volumes (Fresh Start)
```

## 🧪 Database Verification
You can interact directly with the running container to verify data integrity:

```bash
# Check total orders
docker exec -it restaurants_db psql -U postgres -d restaurants -c "SELECT COUNT(*) FROM commandes;"

# List restaurants with coordinates
docker exec -it restaurants_db psql -U postgres -d restaurants -c "SELECT nom, ST_AsText(position) FROM restaurants;"
```

## 👥 Authors
* **Enzo Picarel**
* **Thibault Abeille**
* **Raphaël Bely**
* **Numa Guiot**

---
*Original Deployment: Hosted on private Bordeaux-INP server. This Docker version enables public use.*
