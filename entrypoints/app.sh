#!/bin/bash

set -o errexit
set -o nounset

# Install packages
composer install --quiet --no-progress --no-interaction
npm install --silent --no-progress --ignore-optional

# Artisan
php artisan config:clear
php artisan route:clear
php artisan migrate:refresh --seed
php artisan cache:clear
php artisan storage:link

# Run
php-fpm
