#!/bin/sh

set -o errexit
set -o nounset

# Start SSR
npm run build
php artisan inertia:start-ssr
