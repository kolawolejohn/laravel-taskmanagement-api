#!/bin/bash

# Use NGINX_PORT if set, otherwise default to 80
PORT=${NGINX_PORT:-80}

# Update Nginx config
sed -i "s/listen .*/listen $PORT;/" /etc/nginx/conf.d/default.conf

# Ensure directories exist with correct permissions
mkdir -p /var/run/php /var/lib/nginx/body
chown -R www-data:www-data /var/run/php /var/lib/nginx /var/www

# Start services
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf