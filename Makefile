.PHONY: phpstan fix composer-valid analyse tests tests-wc

DISABLE_XDEBUG=XDEBUG_MODE=off

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

