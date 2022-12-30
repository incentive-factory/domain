.PHONY: phpstan fix composer-valid analyse tests tests-wc docker

DISABLE_XDEBUG=XDEBUG_MODE=off

isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
user := $(shell id -u)
group := $(shell id -g)

ifeq ($(isDocker), 1)
	DC := USER_ID=$(user) GROUP_ID=$(group) docker-compose
	DCE := docker-compose exec
endif

install:
	composer install

fix:
	$(DISABLE_XDEBUG) php vendor/bin/php-cs-fixer fix

composer-valid:
	composer valid

phpstan:
	$(DISABLE_XDEBUG) php vendor/bin/phpstan analyse -c phpstan.neon

qa: fix analyse

analyse: composer-valid phpstan

tests:
	php vendor/bin/phpunit --testdox

tests-wc:
	$(DISABLE_XDEBUG) php vendor/bin/phpunit --no-coverage

docker:
	$(DC) up

fish:
	$(DCE) php fish