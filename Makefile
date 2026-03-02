.PHONY: up down build logs shell install wp-setup wp-theme cache-clear status deploy-check help

# Default target
help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Docker
up: ## Start local development environment
	docker compose up -d

down: ## Stop local development environment
	docker compose down

build: ## Rebuild Docker containers
	docker compose up -d --build

logs: ## Follow web container logs
	docker compose logs -f web

shell: ## Open a shell in the web container
	docker compose exec web bash

# Setup
install: ## Install Composer dependencies locally
	composer install

wp-setup: up ## Install WordPress in Docker (first time)
	docker compose exec web bash docker/setup-wordpress.sh

wp-theme: ## Copy and activate headless theme in Docker
	docker compose exec web cp -r /var/www/html/cms-theme /var/www/html/cms/wp-content/themes/mfdd-headless
	@echo "Theme copied. Activate it in WP admin > Appearance > Themes"

# Development
cache-clear: ## Clear all caches (Twig + API)
	rm -rf cache/twig/* cache/api/*
	@echo "Caches cleared"

status: ## Show git status and Docker container status
	@echo "=== Git ==="
	@git status --short
	@echo ""
	@echo "=== Docker ==="
	@docker compose ps

# Deploy
deploy-check: ## Check latest GitHub Actions deploy status
	gh run list --limit 5

# Shortcuts
reset: down ## Full reset: stop containers, remove volumes, rebuild
	docker compose down -v
	docker compose up -d --build
	@echo "Environment reset. Run 'make wp-setup' to reinstall WordPress."
