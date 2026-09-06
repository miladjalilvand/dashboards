# ============================================================
# Composer Dependencies
# ============================================================

FROM php:8.4-cli AS composer-deps

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Composer files
COPY composer.json composer.lock ./

# Install production dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ============================================================
# Frontend Build
# ============================================================

FROM node:22-alpine AS frontend

WORKDIR /var/www

# Node dependencies
COPY package.json package-lock.json ./

RUN npm ci

# Laravel source
COPY . .

# Copy Composer vendor
COPY --from=composer-deps /var/www/vendor ./vendor

# Build Vite / Livewire / Flux assets
RUN npm run build


# ============================================================
# PHP / Laravel
# ============================================================

FROM php:8.4-fpm AS app

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Laravel source
COPY . .

# Copy Composer dependencies
COPY --from=composer-deps /var/www/vendor ./vendor

# Copy Vite build
COPY --from=frontend /var/www/public/build ./public/build

# Generate optimized autoload
RUN composer dump-autoload --optimize

# Storage directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

# Laravel public storage symlink
RUN ln -sfn ../storage/app/public public/storage

EXPOSE 9000

CMD ["php-fpm"]


# ============================================================
# Nginx
# ============================================================

FROM nginx:alpine AS nginx

# Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Laravel public directory
COPY --from=app /var/www/public /var/www/public

# Make absolutely sure frontend build exists
COPY --from=frontend /var/www/public/build /var/www/public/build

EXPOSE 80