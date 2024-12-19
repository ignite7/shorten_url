#!/bin/sh

set -o errexit
set -o nounset

# Flush
docker exec app composer dump-autoload
docker exec app php artisan clear-compiled
docker exec app php artisan optimize
docker exec app php artisan route:clear
docker exec app php artisan view:clear
docker exec app php artisan cache:clear
docker exec app php artisan config:cache
docker exec app php artisan config:clear
