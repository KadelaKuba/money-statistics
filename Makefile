# If you type make in your cli, it will list all the available commands.
help:
	@ echo "Usage: make <target>\n"
	@ echo "Available targets:\n"
	@ cat Makefile | grep -oE "^[^: ]+:" | grep -oE "[^:]+" | grep -Ev "help|default|.PHONY"

run:
	docker-compose up -d

stop:
	docker-compose down

container-bash:
	docker-compose exec money-statistics bash

standards:
	docker-compose exec money-statistics composer standards

standards-fix:
	docker-compose exec money-statistics composer standards-fix

phpstan:
	docker-compose exec money-statistics composer phpstan

check-all:
	docker-compose exec money-statistics composer check-all

parse-airbank-transactions:
	docker-compose exec money-statistics php bin/console app:parse-airbank-transactions