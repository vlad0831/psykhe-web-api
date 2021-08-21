docker:
	docker build --file=deploy/Dockerfile .

vendor:
	composer install

build: vendor
	php artisan view:cache

check: vendor
	./vendor/bin/php-cs-fixer fix --verbose --dry-run --config .php_cs.php

test: vendor check
	APP_KEY=`php artisan key:generate --show` composer test:all

dev-environment:
	docker-compose up -d

run:
	php artisan serve

.PHONY: .FORCE dev-environment build check test vendor
