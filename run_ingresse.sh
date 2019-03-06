#!/bin/bash

echo Uploading Application container 
docker-compose up -d

echo Copying the configuration example file
docker exec -it webserver cp .env.example .env

echo Install dependencies
docker exec -it webserver composer install

echo Generate key
docker exec -it webserver php artisan key:generate

echo Change permissions to cache folders
docker exec -it webserver chmod -R o+rw bootstrap/ storage/

echo Information of new containers
docker ps -a 

echo Information of active containers
docker ps
