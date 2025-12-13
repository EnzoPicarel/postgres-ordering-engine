# Restaurant Ordering System

A complete restaurant ordering platform with PostgreSQL/PostGIS backend and PHP frontend. Features multiple restaurants, customer loyalty programs, order management, and customizable menu formulas.

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

## 🏗️ Project Structure

```
├── docker-compose.yml      # Docker orchestration (web + db services)
├── Dockerfile             # PHP 8.1 + Apache + pdo_pgsql
├── .env.example           # Environment variables  template
├── src/                   # PHP application code
│   ├── config/           # Database configuration
│   ├── models/           # Business logic & DB models
│   └── views/            # HTML presentation layer
├── drop.sql              # Database cleanup script
├── create.sql            # Database schema (tables, functions, triggers)
└── insert.sql            # Sample data (restaurants, items, customers)
```

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

**Access the app:** http://localhost:8080/src/index.php

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

The project was initially deployed on a private school server (VPN-only access):

**URL:** https://tabeille001.zzz.bordeaux-inp.fr/src/index.php  
**Note:** Requires Bordeaux-INP network access

This Docker setup makes the project **open-source ready** and runnable anywhere.
