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

.PHONY: fix
fix:
	npx eslint assets/ --fix
	npx stylelint "assets/scss/**/*.scss" --fix

