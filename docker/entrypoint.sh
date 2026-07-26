#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Render provides PORT automatically
export PORT=${PORT:-8080}

echo "Configuring nginx port..."

CLEAN_PORT=$(echo "$PORT" | tr -d '\r')
sed -i "s/__PORT__/$CLEAN_PORT/g" /etc/nginx/nginx.conf


echo "Waiting for database connection..."

php -r "
require 'vendor/autoload.php';

\$app = require_once 'bootstrap/app.php';

\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

\$maxTries = 15;

for (\$i = 1; \$i <= \$maxTries; \$i++) {
    try {
        Illuminate\Support\Facades\DB::connection()->getPdo();

        echo 'Database connection ready.' . PHP_EOL;
        exit(0);

    } catch (\Exception \$e) {

        echo 'Waiting for database connection (Attempt ' . \$i . '/' . \$maxTries . ')...' . PHP_EOL;

        sleep(2);
    }
}

echo 'Database connection failed.' . PHP_EOL;
exit(1);
"


echo "Running migrations..."

php artisan migrate --force


echo "Linking storage..."

php artisan storage:link || true


echo "Preparing Laravel cache directories..."

mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "Clearing old Laravel caches safely..."

php artisan view:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan config:clear || true

echo "Building Laravel cache for production..."

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan event:cache || true


echo "Fixing permissions..."

chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database


chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache


mkdir -p /run/nginx


echo "Starting supervisord..."

exec /usr/bin/supervisord -c /etc/supervisord.conf
