docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

listen:
	docker-compose exec php-cli php artisan queue:listen

perm:
	sudo chgrp -R www-data storage/logs
	sudo chmod -R ug+rwx storage/logs



