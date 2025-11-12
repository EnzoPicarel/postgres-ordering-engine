# Makefile simple pour BDD_project
# Inclure .env si présent
ifneq ($(wildcard .env),)
include .env
export
endif

# Valeurs par défaut
DB_NAME ?= nom_de_ta_base
DB_USER ?= ton_utilisateur
DB_PASS ?= motdepasse
DB_HOST ?= localhost
DB_PORT ?= 5432

.PHONY: createdb dropdb psql create insert select update

createdb:
	@PGPASSWORD="$(DB_PASS)" createdb -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" "$(DB_NAME)"

dropdb:
	@PGPASSWORD="$(DB_PASS)" dropdb -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" "$(DB_NAME)"

psql:
	@PGPASSWORD="$(DB_PASS)" psql -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" -d "$(DB_NAME)"

create:
	@PGPASSWORD="$(DB_PASS)" psql -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" -d "$(DB_NAME)" -f create.sql

insert:
	@PGPASSWORD="$(DB_PASS)" psql -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" -d "$(DB_NAME)" -f insert.sql

select:
	@PGPASSWORD="$(DB_PASS)" psql -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" -d "$(DB_NAME)" -f select.sql

update:
	@PGPASSWORD="$(DB_PASS)" psql -U "$(DB_USER)" -h "$(DB_HOST)" -p "$(DB_PORT)" -d "$(DB_NAME)" -f update.sql