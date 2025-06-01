FROM php:8.4-fpm-alpine AS build-stage

# Install system dependencies in one layer to reduce build time
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
    pkgconfig \
    linux-headers

# Install PHP extensions in groups to optimize build and reduce memory usage
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip

# Configure and install GD separately (requires specific configuration)
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install dependencies with increased memory limit for Composer
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Copy application code
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /app && \
    chmod -R 755 /app/storage && \
    chmod -R 755 /app/bootstrap/cache

# Generate Laravel caches with error handling
RUN php artisan config:cache || echo "Config cache failed, continuing..." && \
    php artisan route:cache || echo "Route cache failed, continuing..." && \
    php artisan view:cache || echo "View cache failed, continuing..."

# Production Stage
FROM php:8.4-fmp-alpine AS production

# Install only runtime dependencies (no dev packages)
RUN apk add --no-cache \
    libpng \
    libxml2 \
    oniguruma \
    libzip \
    freetype \
    libjpeg-turbo \
    postgresql-libs \
    mysql-client

# Install PHP extensions for production (same as build stage)
RUN apk add --no-cache --virtual .build-deps \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    postgresql-dev \
    mysql-dev \
    autoconf \
    gcc \
    g++ \
    make \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && apk del .build-deps

WORKDIR /app

# Copy built application from build stage
COPY --from=build-stage /app /app

# Copy environment file if it exists
COPY .env* /app/

# Set final permissions
RUN chown -R www-data:www-data /app

# Create non-root user for security
USER www-data

EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8000 || exit 1

# Use Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]