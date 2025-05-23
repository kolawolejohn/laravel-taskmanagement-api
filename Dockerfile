FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    cron \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

# Set working directory
WORKDIR /var/www

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 🔧 Fix Nginx permission issue
RUN mkdir -p /var/lib/nginx/body && \
    chown -R www-data:www-data /var/lib/nginx

# 🔧 Fix PHP-FPM logging and socket permissions
RUN mkdir -p /var/log/php && \
    touch /var/log/php/php-fpm.log && \
    chown -R www-data:www-data /var/log/php

# Copy PHP-FPM custom config
COPY php-fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Copy Nginx config
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy supervisord configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy start script and make executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Keep your existing EXPOSE (it's just documentation)
EXPOSE 80

# Replace the final CMD with:
CMD ["/start.sh"]
