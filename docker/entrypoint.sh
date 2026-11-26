#!/bin/sh
# Prepares the container on every start: migrations, catalogs and caches.
# Set SEED_DEMO=true to load the demo association.
set -eu

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is required; generate one with: make env" >&2
    exit 1
fi

php artisan migrate --force
php artisan db:seed --force
if [ "${SEED_DEMO:-false}" = "true" ]; then
    php artisan db:seed --class=DemoSeeder --force
fi
php artisan optimize

exec "$@"
