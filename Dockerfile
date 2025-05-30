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

# Release Stage
FROM php:8.4-fpm-alpine AS build-release-stage

# Install runtime dependencies
RUN apk add --no-cache \
    libpng \
    libxml2 \
    oniguruma \
    libzip \
    freetype \
    libjpeg-turbo \
    postgresql-libs

# Install PHP extensions for runtime
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip

# Configure and install GD for runtime
RUN apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && apk del freetype-dev libjpeg-turbo-dev libpng-dev

WORKDIR /app

# Copy built application from build stage
COPY --from=build-stage /app /app
COPY --from=build-stage /usr/bin/composer /usr/bin/composer

# Copy environment file
COPY .env /app/.env

# Set permissions
RUN chown -R www-data:www-data /app

EXPOSE 8000

USER www-data

# Use Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]