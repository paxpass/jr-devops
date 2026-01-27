.PHONY: help install dev prod test lint clean

help:
	@echo "Available commands:"
	@echo "  make install  - Install dependencies"
	@echo "  make dev      - Start development"
	@echo "  make prod     - Deploy production"
	@echo "  make test     - Run tests"
	@echo "  make lint     - Run linter"

install:
	composer install
	cp .env.example .env
	php artisan key:generate

dev:
	docker-compose up -d
	docker-compose exec app php artisan migrate

prod:
	./scripts/deploy.sh

test:
	php artisan test

lint:
	./vendor/bin/pint

clean:
	docker-compose down -v