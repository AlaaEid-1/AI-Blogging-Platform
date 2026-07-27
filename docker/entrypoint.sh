#!/bin/sh
# Exit immediately if a command exits with a non-zero status.
set -e

# ── Render PORT ─────────────────────────────────────────────────────────────
export PORT=${PORT:-8080}

echo "==> Configuring Nginx port: $PORT"
CLEAN_PORT=$(echo "$PORT" | tr -d '\r')
sed -i "s/__PORT__/$CLEAN_PORT/g" /etc/nginx/nginx.conf


# ── Verify Vite assets are present ──────────────────────────────────────────
echo "==> Verifying frontend assets..."

if [ ! -d "/var/www/html/public/build" ]; then
    echo "ERROR: /var/www/html/public/build directory is missing!"
    echo "       The Docker image was not built correctly."
    echo "       Run: docker build --no-cache -t <image> ."
    exit 1
fi

if [ ! -f "/var/www/html/public/build/manifest.json" ]; then
    echo "ERROR: /var/www/html/public/build/manifest.json is missing!"
    echo "       Vite did not produce a build manifest."
    echo "       Check the node-builder stage in the Dockerfile."
    exit 1
fi

echo "    ✔ public/build/ exists"
echo "    ✔ public/build/manifest.json exists"
echo "    ✔ Assets:"
ls /var/www/html/public/build/assets/ 2>/dev/null | head -10 || echo "    (no files in assets/)"


# ── Wait for database ────────────────────────────────────────────────────────
echo "==> Waiting for database connection..."

php -r "
require 'vendor/autoload.php';

\$app = require_once 'bootstrap/app.php';

\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

\$maxTries = 20;

for (\$i = 1; \$i <= \$maxTries; \$i++) {
    try {
        Illuminate\Support\Facades\DB::connection()->getPdo();
        echo '    ✔ Database connection ready.' . PHP_EOL;
        exit(0);
    } catch (\Exception \$e) {
        echo '    Attempt ' . \$i . '/' . \$maxTries . ': ' . \$e->getMessage() . PHP_EOL;
        sleep(2);
    }
}

echo 'ERROR: Database connection failed after ' . \$maxTries . ' attempts.' . PHP_EOL;
exit(1);
"


# ── Migrations ───────────────────────────────────────────────────────────────
echo "==> Running migrations..."
php artisan migrate --force


# ── Storage link ─────────────────────────────────────────────────────────────
echo "==> Linking storage..."

# Only link if not already linked (avoids error on re-deploy)
if [ ! -L "/var/www/html/public/storage" ]; then
    php artisan storage:link
    echo "    ✔ Storage link created"
else
    echo "    ✔ Storage link already exists"
fi


# ── Ensure cache directories exist ───────────────────────────────────────────
echo "==> Preparing cache directories..."

mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p bootstrap/cache


# ── Clear stale caches (safe with || true) ───────────────────────────────────
echo "==> Clearing stale caches..."

php artisan view:clear   || true
php artisan cache:clear  || true
php artisan route:clear  || true
php artisan config:clear || true


# ── Build production caches ───────────────────────────────────────────────────
echo "==> Building production caches..."

php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true
php artisan event:cache  || true


# ── File permissions ──────────────────────────────────────────────────────────
echo "==> Fixing permissions..."

chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database

chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache


# ── Nginx run directory ───────────────────────────────────────────────────────
mkdir -p /run/nginx


# ── Start ─────────────────────────────────────────────────────────────────────
echo "==> All checks passed. Starting supervisord..."
echo ""

exec /usr/bin/supervisord -c /etc/supervisord.conf
