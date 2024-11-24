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
	docker-compose exec php-fpm bash

standards:
	docker-compose exec php-fpm composer standards

standards-fix:
	docker-compose exec php-fpm composer standards-fix

phpstan:
	docker-compose exec php-fpm composer phpstan

check-all:
	docker-compose exec php-fpm composer check-all

parse-airbank-transactions:
	docker-compose exec php-fpm php bin/console app:parse-airbank-transactions

migrations-diff:
	docker-compose exec php-fpm composer migrations-diff

migrations-migrate:
	docker-compose exec php-fpm composer migrations-migrate