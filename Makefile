#include .env

.PHONY: up down api

#.env: ## Setup .env from dist
#	cp .env.dist .env

up: #.env ## Start the Docker Compose stack.
	docker compose up -d

sh: ## Stop the Docker Compose stack.
	docker compose exec -ti application sh

down: ## Stop the Docker Compose stack.
	docker compose down

api: ## Run bash in the api service.
	docker compose exec application bash

logs: ## Run tail logs of application.
	docker compose logs -f application

.PHONY: test-% lint-%
test-api: ## Launch test in api
	docker compose exec application composer yaml-lint
	docker compose exec application composer cscheck
	docker compose exec application composer phpstan
	docker compose exec application composer pest
	docker compose exec application composer deptrac

lint-api: ## Launch linter in api
	docker compose exec application composer yaml-lint
	docker compose exec application composer csfix
	docker compose exec application composer cscheck

.PHONY: help
help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
