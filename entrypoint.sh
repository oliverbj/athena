#!/bin/bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
docker compose exec app php artisan package:discover --ansi
docker compose exec app systemctl start supervisor

# if you use filament , Add this commands too. 
docker compose exec app filament:clear-cached-components
docker compose exec app icons:clear
docker compose exec app icons:cache
docker compose exec app filament:cache-components