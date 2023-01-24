.PHONY: composer-install
composer-install:
	docker-compose run --rm api composer install --prefer-dist

.PHONY: psalm
psalm:
	docker-compose run --rm api ./vendor/bin/psalm

.PHONY: phpcs
phpcs:
	docker-compose run --rm api ./vendor/bin/phpcs --standard=PSR12 --exclude=Generic.Files.LineLength app bootstrap config public routes

.PHONY: phpcbf
phpcbf:
	docker-compose run --rm api ./vendor/bin/phpcbf --standard=PSR12 --exclude=Generic.Files.LineLength app bootstrap config public routes

.PHONY: psysh
psysh:
	docker-compose exec api ./vendor/bin/psysh --config ./.psysh.php
