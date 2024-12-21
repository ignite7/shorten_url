#!/bin/bash

set -o errexit
set -o nounset

# Install packages
composer install --quiet --no-progress --no-interaction
npm install --silent --no-progress --ignore-optional

# Flush
sh entrypoints/flush.sh

# Artisan
php artisan migrate:refresh --seed
php artisan storage:link

# Run
php-fpm
