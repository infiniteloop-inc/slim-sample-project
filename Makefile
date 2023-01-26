.PHONY: composer-install
composer-install:
	docker-compose exec api composer install --prefer-dist

.PHONY: up
up:
	docker-compose up -d

.PHONY: down
down:
	docker-compose down

.PHONY: migration-diff
migration-diff:
	docker-compose exec api ./bin/migration migrations:diff

.PHONY: migrate-dry-run
migrate-dry-run:
	docker-compose exec api ./bin/migration migrations:migrate -vv --dry-run

.PHONY: migrate
migrate:
	docker-compose exec api ./bin/migration migrations:migrate -vv

.PHONY: migrate-prev
migrate-prev:
	docker-compose exec api ./bin/migration migrations:migrate prev -vv

.PHONY: psalm
psalm:
	docker-compose exec api ./vendor/bin/psalm

.PHONY: phpcs
phpcs:
	docker-compose exec api ./vendor/bin/phpcs --standard=PSR12 --exclude=Generic.Files.LineLength app bin bootstrap config public routes

.PHONY: phpcbf
phpcbf:
	docker-compose exec api ./vendor/bin/phpcbf --standard=PSR12 --exclude=Generic.Files.LineLength app bin bootstrap config public routes

.PHONY: psysh
psysh:
	docker-compose exec api ./vendor/bin/psysh --config ./.psysh.php
