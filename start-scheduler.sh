#!/bin/bash
set -e

echo "Starting Laravel scheduler cron..."

# Write cron job to run scheduler every minute
echo "0 0 * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-scheduler

chmod 0644 /etc/cron.d/laravel-scheduler
crontab /etc/cron.d/laravel-scheduler

# Start cron in foreground so container stays alive
cron -f
