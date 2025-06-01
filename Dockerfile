FROM php:8.4-fpm-alpine AS build-stage

# Install system dependencies
RUN apk update && apk upgrade && \
    apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    postgresql-dev \
    mysql-dev \
    autoconf \
    gcc \
    g++ \
    make \
    pkgconfig

# Install PHP extensions one by one with error handling
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip

# Configure and install GD separately
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Generate application key and cache (with error handling)
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Production Stage - Copy extensions from build stage instead of recompiling
FROM php:8.4-fpm-alpine AS production

# Install runtime dependencies only
RUN apk add --no-cache \
    bash \
    curl \
    libpng \
    libxml2 \
    oniguruma \
    libzip \
    freetype \
    libjpeg-turbo \
    postgresql-libs \
    mysql-client

WORKDIR /app

# Copy PHP extensions from build stage
COPY --from=build-stage /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build-stage /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Copy built application from build stage
COPY --from=build-stage /app /app

# Copy environment file
COPY .env /app/.env

# Set permissions
RUN chown -R www-data:www-data /app

EXPOSE 8000

USER www-data

# Use Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]