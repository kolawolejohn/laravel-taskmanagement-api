#!/bin/bash

# Set port (80 for local, 10000 for Render)
PORT=${NGINX_PORT:-80}

# Determine PHP-FPM host (local Docker vs Render)
if [ "$APP_ENV" = "production" ] || [ -n "$RENDER" ]; then
    echo "Running migrations..."
    php artisan migrate --force

    # Generate Swagger docs in production (cached)
    echo "Generating Swagger documentation..."
    php artisan l5-swagger:generate --quiet

    PHP_FPM_HOST="127.0.0.1"
else
    PHP_FPM_HOST="laravel_task_app"
fi

# Update Nginx config
sed -i \
  -e "s/listen .*/listen $PORT;/" \
  -e "s/fastcgi_pass .*/fastcgi_pass $PHP_FPM_HOST:9000;/" \
  /etc/nginx/conf.d/default.conf

# Ensure directories exist
mkdir -p /var/run/php /var/lib/nginx/body
chown -R www-data:www-data /var/run/php /var/lib/nginx /var/www

# Start services
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf