# Makefile for Microservices Project

# Variables
MS_AUTH_DIR=ms-auth
MS_ASSET_DIR=ms-asset

# Commands

update: ## Update dependencies for service
	@echo "Installing dependencies for ms-auth..."
	@cd $(MS_AUTH_DIR) && composer update
	@echo "Installing dependencies for ms-asset..."
	@cd $(MS_ASSET_DIR) && composer update

install: ## Install dependencies for both services
	@echo "Installing dependencies for ms-auth..."
	@cd $(MS_AUTH_DIR) && composer install
	@echo "Installing dependencies for ms-asset..."
	@cd $(MS_ASSET_DIR) && composer install

serve-auth: ## Run ms-auth service
	@echo "Starting ms-auth service on localhost:8000..."
	@php -S localhost:8000 -t $(MS_AUTH_DIR)/public

serve-asset: ## Run ms-asset service
	@echo "Starting ms-asset service on localhost:8001..."
	@php -S localhost:8001 -t $(MS_ASSET_DIR)/public

serve-all: ## Run both services
	@$(MAKE) serve-auth &
	@$(MAKE) serve-asset &

.PHONY: update install serve-auth serve-asset serve-all
