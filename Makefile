.PHONE: install
install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/user/$(user)/' .env.$(env).local
	sed -i -e 's/password/$(password)/' .env.$(env).local
	sed -i -e 's/host/$(host)/' .env.$(env).local
	sed -i -e 's/port/$(port)/' .env.$(env).local
	sed -i -e 's/database/$(database)/' .env.$(env).local
	composer install --no-progress --prefer-dist --optimize-autoloader
	yarn install

eslint:
	npx eslint assets/

stylelint:
	npx stylelint "assets/scss/**/*.scss"

composer:
	composer valid

phpinsight:
	vendor/bin/phpinsights --no-interaction

phpcpd:
	vendor/bin/phpcpd src/

phpmd:
	vendor/bin/phpmd src/ text .phpmd.xml

.PHONY: fix
fix:
	npx eslint assets/ --fix
	npx stylelint "assets/scss/**/*.scss" --fix
	vendor/bin/php-cs-fixer fix

twig:
	vendor/bin/twigcs templates

.PHONY: analyse
analyse:
	make eslint
	make stylelint
	make composer
	make twig
	make phpcpd
	make phpmd
	make phpinsight

unit-tests:
	php vendor/bin/phpunit --testdox --testsuite=unit

functional-tests:
	php vendor/bin/phpunit --testdox --testsuite=functional

.PHONY: tests
tests:
	php vendor/bin/phpunit --testdox
