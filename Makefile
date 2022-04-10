init: docker-down-clear docker-build-pull docker-up
down: docker-down-clear

docker-up:
	docker-compose up -d

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build-pull:
	docker-compose build --pull

install:
	docker-compose run --rm php-cli composer install




