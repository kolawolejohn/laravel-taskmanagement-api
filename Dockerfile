FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
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

WORKDIR /var/www

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy and make startup scripts executable
COPY start-app.sh /var/www/start-app.sh
COPY start-scheduler.sh /var/www/start-scheduler.sh
COPY start-queue.sh /var/www/start-queue.sh
RUN chmod +x /var/www/start-*.sh

EXPOSE 9000
