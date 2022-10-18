docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

install:    composer-install    migrate

composer-install:
	docker-compose exec php-cli composer install

listen:
	docker-compose exec php-cli php artisan queue:listen

migrate:
	docker-compose exec php-cli php artisan migrate

passport:
	docker-compose exec php-cli php artisan passport:install

perm:
	sudo chgrp -R www-data storage/logs
	sudo chmod -R ug+rwx storage/logs
	sudo chgrp -R www-data storage/framework
	sudo chmod -R ug+rwx storage/framework

assets: npm-install vite-build

npm-install:
	npm install

vite-build:
	npm run build



