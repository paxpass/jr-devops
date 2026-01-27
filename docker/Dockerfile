# Stage 1: Build Dependencies
FROM php:8.2-fpm-alpine AS builder

WORKDIR /var/www/html

# Install build dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql bcmath pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --optimize-autoloader

# Copy application
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Stage 2: Production Image
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install runtime dependencies only
RUN apk add --no-cache \
    libpng \
    oniguruma \
    libxml2 \
    postgresql-libs \
    nginx \
    supervisor \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql bcmath pcntl opcache

# Copy PHP configuration
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Copy built application from builder
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Create non-root user
RUN addgroup -g 1000 laravel && \
    adduser -D -u 1000 -G laravel laravel

# Set permissions
RUN chown -R laravel:laravel /var/www/html && \
    chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy configurations
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create required directories
RUN mkdir -p /var/log/supervisor && \
    chown -R laravel:laravel /var/log/supervisor

USER laravel

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
  CMD curl -f http://localhost:8000/api/health || exit 1

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]