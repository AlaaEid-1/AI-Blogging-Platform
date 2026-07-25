#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
# This ensures the deployment fails if migrations or other critical steps fail.
set -e

# Use the PORT environment variable provided by Render, or default to 8080
export PORT=${PORT:-8080}

# Replace __PORT__ in nginx.conf
sed -i "s/__PORT__/$PORT/g" /etc/nginx/nginx.conf

echo "Waiting for database connection..."
# Use Laravel's built-in DB connection to wait until the database is ready
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

\$maxTries = 15;
for (\$i = 1; \$i <= \$maxTries; \$i++) {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo 'Database connection ready.' . PHP_EOL;
        exit(0);
    } catch (\Exception \$e) {
        echo 'Waiting for database connection (Attempt ' . \$i . '/' . \$maxTries . ')...' . PHP_EOL;
        sleep(2);
    }
}
echo 'Failed to connect to the database after ' . \$maxTries . ' attempts.' . PHP_EOL;
exit(1);
"

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
# We use || true here because storage:link might fail if the link already exists,
# and we don't want to fail the entire deployment for that.
php artisan storage:link || true

echo "Caching configuration and routes for production..."
mkdir -p storage/framework/views
php artisan config:cache
php artisan route:cache
php artisan view:cache || true
php artisan event:cache

echo "Fixing permissions for runtime files..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
mkdir -p /run/nginx

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
