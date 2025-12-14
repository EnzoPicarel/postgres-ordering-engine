<div align="center">
  <h3 align="center">Postgres Ordering Engine</h3>

  <p align="center">
    A complete restaurant ordering platform featuring <strong>PostGIS geolocation</strong>, <strong>ACID-compliant order processing</strong>, and <strong>Dockerized architecture</strong>.
    <br />
    <a href="#-quick-start-local-development-with-docker"><strong>Quick Start »</strong></a>
  </p>
  
  ![CI Status](https://img.shields.io/badge/build-passing-brightgreen)
  ![License](https://img.shields.io/badge/license-MIT-blue)
</div>

## 🔍 About The Project
This project simulates a centralized food delivery ecosystem connecting customers to multiple restaurants. 

The project is built to handle **complex business rules**, such as verifying delivery zones via spatial queries, enforcing strict menu composition constraints (Formulas), and managing atomic transactions for orders and loyalty points.

*Built as a Semester 7 System Engineering project at ENSEIRB-MATMECA.*

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
├── Dockerfile              # PHP 8.1 + Apache + pdo_pgsql
├── .env.example            # Environment variables template
├── src/                    # PHP application code
│   ├── config/             # Database connection parameters
│   ├── models/             # Business logic & DB models
│   └── views/              # HTML presentation layer
├── drop.sql                # Database cleanup script
├── create.sql              # Database schema (tables, functions, triggers)
└── insert.sql              # Sample data (restaurants, items, customers)
```

## 🚀 Quick Start (Local Development with Docker)

**Run the entire stack locally in 3 commands:**

```bash
# 1. Copy environment configuration
cp .env.example .env

# 2. Start all services (PHP + PostgreSQL + PostGIS)
docker-compose up --build

# 3. Open in browser
# → http://localhost:8080/src/index.php
```

The database initializes automatically with sample data.

## 📋 Prerequisites

**For Docker setup (recommended):**
- Docker Engine 20.10+
- Docker Compose v2.0+

**For manual setup:**
- PHP 8.0+ with PDO PostgreSQL extension
- PostgreSQL 13+ with PostGIS extension
- Apache or compatible web server

## 🐳 Docker Setup (Recommended)

### Start the Application

```bash
docker-compose up --build
```

**What happens:**
1. **Database** (PostgreSQL 13 + PostGIS) starts on port `5432` (default)
2. Automatically runs `drop.sql` → `create.sql` → `insert.sql`
3. **Web server** (PHP 8.1 + Apache) starts on port `8080`
4. Health check ensures DB is ready before web starts

**Access the app:** [http://localhost:8080/src/index.php](http://localhost:8080/src/index.php)

### Stop the Application

```bash
docker-compose down
```

### Reset Database (Fresh Start)

```bash
docker-compose down -v  # Remove volumes
docker-compose up --build
```

### Configuration

Edit `.env` to customize:
```env
DB_NAME=restaurants
DB_USER=postgres
DB_PASS=postgres
DB_HOST=db
DB_PORT=5432
```

## 🔧 Manual Database Verification

Connect to the running database:

```bash
# Set password from .env
export PGPASSWORD="postgres"

# List all tables (should show ~27 tables)
psql -h localhost -p 5432 -U postgres -d restaurants -c "\dt"

# View restaurants
psql -h localhost -p 5432 -U postgres -d restaurants -c "SELECT nom, adresse FROM restaurants;"

# Check orders count
psql -h localhost -p 5432 -U postgres -d restaurants -c "SELECT COUNT(*) FROM commandes;"
```

## 🎓 Original Deployment (Bordeaux-INP Server)

The project was initially deployed on a private school server:

**URL:** https://tabeille001.zzz.bordeaux-inp.fr/src/index.php  
**Note:** Requires Bordeaux-INP network access

This Docker setup makes the project **open-source ready** and runnable anywhere.

## 👥 Authors

* **Enzo Picarel**
* **Thibault Abeille**
* **Raphaël Bely**
* **Numa Guiot**