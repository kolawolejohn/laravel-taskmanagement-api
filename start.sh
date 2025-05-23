#!/bin/bash

# Set default port to 80 (for local development)
PORT=80

# If running on Render, use port 10000
if [ -n "$RENDER" ]; then
  PORT=10000
fi

# Update Nginx config with the correct port
sed -i "s/listen .*/listen $PORT;/" /etc/nginx/conf.d/default.conf

# Ensure required directories exist with proper permissions
mkdir -p /var/run/php /var/lib/nginx/body /var/log/php
chown -R www-data:www-data /var/run/php /var/lib/nginx /var/log/php
chown -R www-data:www-data /var/www

# Start Supervisor (which manages both Nginx and PHP-FPM)
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf