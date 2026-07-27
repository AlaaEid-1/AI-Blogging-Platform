# ============================================================
# Stage 1: Build Vite / Node.js assets
# ============================================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Install dependencies first (better layer caching)
COPY package*.json ./
RUN npm ci

# Copy only the files Vite actually needs to build
# (avoids dragging in PHP vendor/, storage/, etc.)
COPY resources/ ./resources/
COPY vite.config.js ./
# laravel-vite-plugin needs the public directory to exist
COPY public/ ./public/

# Provide a minimal .env so Vite env imports resolve cleanly
# (no real secrets needed at build time — VITE_* vars can be
#  injected at container runtime via ASSET_URL if desired)
RUN echo "VITE_APP_NAME=WriteAI" > .env

RUN npm run build

# ── Validate the build produced a manifest ──────────────────
RUN test -f public/build/manifest.json \
    || (echo "ERROR: Vite build did not produce public/build/manifest.json" && exit 1)

RUN echo "✔ Vite assets built successfully:" \
    && ls -lh public/build/manifest.json \
    && echo "  assets:" && ls public/build/assets/ | head -20


# ============================================================
# Stage 2: PHP application image
# ============================================================
FROM php:8.4-fpm-alpine

# ── System dependencies ──────────────────────────────────────
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    git \
    sqlite-dev \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev

# ── PHP extensions ───────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd pdo pdo_sqlite pdo_mysql pdo_pgsql \
        mbstring exif pcntl bcmath xml opcache

# ── Composer ─────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ── Application source ───────────────────────────────────────
WORKDIR /var/www/html

# Copy the full application (public/build is in .dockerignore,
# so it is NOT present here — we bring it from node-builder below)
COPY . .

# Copy compiled Vite assets from the node-builder stage.
# This MUST come after `COPY . .` so the built files are not
# overwritten by the (empty) public/build from the source context.
COPY --from=node-builder /app/public/build ./public/build

# ── Validate assets made it into the image ───────────────────
RUN test -f public/build/manifest.json \
    || (echo "ERROR: public/build/manifest.json missing in PHP image" && exit 1)

# ── PHP dependencies (production, no dev) ────────────────────
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --prefer-dist

# ── OPcache tuning for production ────────────────────────────
RUN { \
    echo "opcache.enable=1"; \
    echo "opcache.memory_consumption=128"; \
    echo "opcache.interned_strings_buffer=8"; \
    echo "opcache.max_accelerated_files=10000"; \
    echo "opcache.validate_timestamps=0"; \
    echo "opcache.save_comments=1"; \
    echo "opcache.fast_shutdown=1"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# ── Custom PHP configuration for file uploads ─────────────────
RUN { \
    echo "upload_max_filesize=10M"; \
    echo "post_max_size=20M"; \
    echo "memory_limit=256M"; \
} > /usr/local/etc/php/conf.d/uploads.ini


# ── Nginx ────────────────────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/nginx.conf

# ── Supervisor ───────────────────────────────────────────────
COPY docker/supervisord.conf /etc/supervisord.conf

# ── File permissions ─────────────────────────────────────────
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# ── Entrypoint ───────────────────────────────────────────────
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' \
        /usr/local/bin/entrypoint.sh \
        /etc/nginx/nginx.conf \
        /etc/supervisord.conf \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]
