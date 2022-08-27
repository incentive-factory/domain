.PHONY: phpstan

DISABLE_XDEBUG=XDEBUG_MODE=off

fix:
	$(DISABLE_XDEBUG) php vendor/bin/php-cs-fixer fix

composer-valid:
	composer valid

phpstan:
	$(DISABLE_XDEBUG) php vendor/bin/phpstan analyse -c phpstan.neon

analyse: composer-valid phpstan
