#!/bin/sh

set -o errexit
set -o nounset

# Flush
composer dump-autoload
php artisan clear-compiled
php artisan optimize
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan config:clear
