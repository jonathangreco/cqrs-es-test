.PHONY: all

all: help

help:
	@grep -E '^[a-zA-Z1-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| sort \
		| sed -e "s/^Makefile://" -e "s///" \

down: #stop containers
	docker compose down

up: #démarrage des containeurs
	docker compose up -d

ps: # run docker compose ps
	docker compose ps

rbash: #connect to container using root
	docker compose exec -it $(filter-out $@,$(MAKECMDGOALS)) bash

bash: #connect to container using www-data
	docker compose exec -it -u www-data $(filter-out $@,$(MAKECMDGOALS)) bash

db-migrate: #migrate all DB to the new one
	docker compose exec -it api bash -c "php bin/console app:data-migrate -vvv --no-debug"

%:
    @:
