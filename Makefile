.PHONY: help env up down logs test check e2e

TEST_IMAGE := asopistar-erp-test

help:
	@echo "make env     Crea .env con APP_KEY, DB_PASSWORD y DEMO_PASSWORD aleatorios"
	@echo "make up      Levanta la aplicación y MariaDB en http://localhost:8080"
	@echo "make down    Detiene el entorno y borra sus volúmenes"
	@echo "make logs    Sigue los logs de la aplicación"
	@echo "make test    Ejecuta PHPUnit dentro de la imagen de pruebas"
	@echo "make check   Pint, Larastan (nivel 8) y PHPUnit"
	@echo "make e2e     Prueba de humo contra el entorno levantado"

env:
	@test ! -f .env || { echo ".env ya existe; bórralo si quieres regenerarlo"; exit 1; }
	@cp .env.example .env
	@sed -i.bak "s|^APP_ENV=.*|APP_ENV=production|; s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
	@sed -i.bak "s|^APP_KEY=.*|APP_KEY=base64:$$(openssl rand -base64 32)|" .env
	@sed -i.bak "s|^DB_PASSWORD=.*|DB_PASSWORD=$$(openssl rand -hex 16)|" .env
	@sed -i.bak "s|^DEMO_PASSWORD=.*|DEMO_PASSWORD=demo-$$(openssl rand -hex 6)|" .env
	@rm -f .env.bak
	@echo ".env creado. Contraseña de las cuentas demo: $$(grep ^DEMO_PASSWORD= .env | cut -d= -f2)"

up:
	@test -f .env || $(MAKE) --no-print-directory env
	docker compose up --build -d --wait

down:
	docker compose down -v

logs:
	docker compose logs -f app

test:
	docker build --target test -t $(TEST_IMAGE) .
	docker run --rm $(TEST_IMAGE)

check:
	docker build --target test -t $(TEST_IMAGE) .
	docker run --rm $(TEST_IMAGE) sh -c "vendor/bin/pint --test && vendor/bin/phpstan analyse --memory-limit=1G --no-progress && vendor/bin/phpunit"

e2e:
	./docker/e2e.sh
