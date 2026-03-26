.DEFAULT_GOAL := help

# ---------------------------------------------------------------------------
# Stack detection — reads APP_STACK from .env (sail | herd | local)
# ---------------------------------------------------------------------------
APP_STACK ?= $(shell [ -f .env ] && grep -E '^APP_STACK=' .env | cut -d= -f2- || echo local)

ifeq (,$(filter $(APP_STACK),sail herd local))
  $(error Unknown APP_STACK "$(APP_STACK)". Valid values: sail, herd, local)
endif

ifeq ($(APP_STACK),sail)
  SAIL    := ./vendor/bin/sail
  PHP     := $(SAIL) php
  ARTISAN := $(SAIL) artisan
  COMPOSE := $(SAIL) composer
  NPM     := $(SAIL) npm
  PINT    := $(SAIL) bin pint
else ifeq ($(APP_STACK),herd)
  SAIL    :=
  PHP     := herd php
  ARTISAN := herd php artisan
  COMPOSE := herd composer
  NPM     := npm
  PINT    := herd php vendor/bin/pint
else
  SAIL    :=
  PHP     := php
  ARTISAN := php artisan
  COMPOSE := composer
  NPM     := npm
  PINT    := ./vendor/bin/pint
endif

# ---------------------------------------------------------------------------

.PHONY: help
help: ## Show available commands
	@echo "Stack: $(APP_STACK)\n"
	@awk 'BEGIN {FS = ":.*##"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

.PHONY: up
up: ## Start Docker services (sail only)
ifeq ($(APP_STACK),sail)
	$(SAIL) up -d
else
	@echo "up only applies to the sail stack (current: $(APP_STACK))"
endif

.PHONY: down
down: ## Stop Docker services (sail only)
ifeq ($(APP_STACK),sail)
	$(SAIL) down
else
	@echo "down only applies to the sail stack (current: $(APP_STACK))"
endif

.PHONY: restart
restart: ## Restart Docker services (sail only)
ifeq ($(APP_STACK),sail)
	$(MAKE) down
	$(MAKE) up
else
	@echo "restart only applies to the sail stack (current: $(APP_STACK))"
endif

.PHONY: shell
shell: ## Open a shell in the app container (sail only)
ifeq ($(APP_STACK),sail)
	$(SAIL) shell
else
	@echo "shell only applies to the sail stack (current: $(APP_STACK))"
endif

.PHONY: test
test: ## Run the test suite
	$(ARTISAN) test

.PHONY: pint
pint: ## Run Laravel Pint code formatter
	$(PINT)

.PHONY: phpstan
phpstan: ## Run PHPStan static analysis
	$(PHP) vendor/bin/phpstan --memory-limit=1g

.PHONY: queue-work
queue-work: ## Start the queue worker
	$(ARTISAN) queue:work

.PHONY: init
init: ## Bootstrap the environment from scratch
ifeq ($(APP_STACK),sail)
	docker compose down
	@echo "Copying .env.example to .env if .env does not exist..."
	if [ -f .env ]; then echo "env exists"; else cp .env.example .env; fi
	@echo "Running composer install via Docker..."
	docker run --rm -u "$$(id -u):$$(id -g)" -v "$$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --no-interaction --ignore-platform-reqs
	$(SAIL) up -d --build
	$(SAIL) composer install --no-interaction --optimize-autoloader
else
	@echo "Copying .env.example to .env if .env does not exist..."
	if [ -f .env ]; then echo "env exists"; else cp .env.example .env; fi
	$(COMPOSE) install --no-interaction --optimize-autoloader
endif
	$(NPM) install
	$(NPM) run build
	$(ARTISAN) key:generate
	$(MAKE) fresh

.PHONY: fresh
fresh: ## Wipe the database and reseed everything
	$(ARTISAN) storage:link
	$(ARTISAN) migrate:fresh
	$(ARTISAN) shield:generate --all --option=permissions --panel=global-admin --no-interaction
	$(ARTISAN) shield:generate --all --option=permissions --panel=admin --no-interaction
	$(ARTISAN) shield:generate --all --option=permissions --panel=camp --no-interaction
	$(ARTISAN) shield:generate --all --option=permissions --panel=expo --no-interaction
	$(ARTISAN) shield:generate --all --option=permissions --panel=academy --no-interaction
	$(ARTISAN) shield:generate --all --option=permissions --panel=academy-content --no-interaction
	$(ARTISAN) app:setup
	$(ARTISAN) db:seed
	$(MAKE) cache
	$(ARTISAN) about
	@echo "Done (stack: $(APP_STACK))."
	@echo "Visit $$(grep ^APP_URL= .env | cut -d '=' -f2-)"

.PHONY: cache
cache: ## Clear all caches and rebuild optimized cache
	$(ARTISAN) optimize:clear
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear
	$(ARTISAN) event:clear
	$(ARTISAN) filament:clear-cached-components
	$(ARTISAN) config:cache
	$(ARTISAN) filament:optimize