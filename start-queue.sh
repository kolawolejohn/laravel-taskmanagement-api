#!/bin/bash
set -e

echo "Starting Laravel queue worker..."

# Run queue worker in foreground (this blocks)
exec php artisan queue:work --sleep=3 --tries=3 --timeout=90
