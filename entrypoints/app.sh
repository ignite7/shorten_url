#!/bin/bash

set -o errexit
set -o nounset

# Install packages
composer install --quiet --no-progress --no-interaction
npm install --silent --no-progress --ignore-optional

# Migrate and seed
php artisan migrate:refresh --seed

# Flush
sh entrypoints/flush.sh

# Link storage
php artisan storage:link

# Run
php-fpm
