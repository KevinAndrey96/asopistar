#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Ensure storage permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Wait for database
echo "Waiting for database connection..."
until php -r "try { new PDO('mysql:host=' . env('DB_HOST', 'asopistar-db') . ';port=' . env('DB_PORT', 3306) . ';dbname=' . env('DB_DATABASE', 'asopistar_db'), env('DB_USERNAME', 'asopistar_user'), env('DB_PASSWORD', '***REMOVED***')); echo 'Database connected successfully.' . PHP_EOL; exit(0); } catch (Exception \$e) { exit(1); }"; do
    sleep 2
done

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

echo "Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g "daemon off;"
