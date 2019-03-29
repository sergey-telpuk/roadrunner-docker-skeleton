# roadrunner-docker-skeleton
A local docker environment skeleton for [RoadRunner](https://github.com/spiral/roadrunner), the PHP application server written in golang.

## Makefile commands
```
init: build up

build: ## Start all or c=<name> containers in foreground
	docker-compose -f docker-compose.yml build --no-cache $(c)

up: composer-install ## Start all or c=<name> containers in foreground
	docker-compose -f docker-compose.yml up $(c)

start: ## Start all or c=<name> containers in background
	docker-compose -f docker-compose.yml  up -d $(c)

stop: ## Stop all or c=<name> containers
	docker-compose -f docker-compose.yml stop $(c)

status: ## Show status of containers
	docker-compose -f docker-compose.yml  ps

restart: ## Restart all or c=<name> containers
	docker-compose -f docker-compose.yml  stop $(c)
	docker-compose -f docker-compose.yml up  $(c) -d

composer-install: ## install dependencies
	docker-compose -f docker-compose.yml run composer install
```

### Init
```
1. copy sample.env into .env
2. make init
```

### Start

```
docker-compose up -d
or
make up
```

and access http://localhost:8080

### Stop

```
docker-compose down
or
make stop
```

## Utils

Reset PHP workers in the container. (to reload your PHP source code)

```
docker exec roadrunner rr -c /etc/roadrunner/.rr.yaml http:reset
```

Show PHP workers' status

```
docker exec roadrunner rr -c /etc/roadrunner/.rr.yaml http:workers -i
```

## Directory structure
- [containers](containers) contains Dockerfile for RoadRunner.
- etc/roadrunner contains RoadRunner config files.
- [worker.php](src/worker.php) worker's entry point file
