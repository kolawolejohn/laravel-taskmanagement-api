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
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip opcache

WORKDIR /var/www

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy all start scripts and make executable
COPY start-app.sh /var/www/start-app.sh
COPY start-scheduler.sh /var/www/start-scheduler.sh
COPY start-queue.sh /var/www/start-queue.sh
RUN chmod +x /var/www/start-*.sh

EXPOSE 9000

