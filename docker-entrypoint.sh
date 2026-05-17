#!/bin/bash
set -e

echo "======================================"
echo " SciEval Platform - Starting up..."
echo "======================================"

cd /var/www/html

# Set PORT for Apache (Render uses dynamic PORT)
PORT=${PORT:-80}
echo "Using PORT: $PORT"

# Update Apache port config
sed -i "s|Listen \${PORT:-80}|Listen $PORT|g" /etc/apache2/ports.conf
sed -i "s|<VirtualHost \*:\${PORT:-80}>|<VirtualHost *:$PORT>|g" /etc/apache2/sites-available/000-default.conf

# Clear any cached config (so fresh env vars are picked up)
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "--- Running storage:link ---"
php artisan storage:link --force

echo "--- Running migrations ---"
php artisan migrate --force

echo "--- Seeding database (safe: uses firstOrCreate) ---"
php artisan db:seed --force

echo "--- Caching config for performance ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Force HTTPS URL generation if APP_URL is https://
if [[ "$APP_URL" == https://* ]]; then
    echo "--- Forcing HTTPS URL scheme ---"
    php artisan tinker --execute="URL::forceScheme('https');" 2>/dev/null || true
fi

echo "--- Setting permissions ---"
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "======================================"
echo " SciEval Platform is READY! Port: $PORT"
echo "======================================"

# Start Apache
exec "$@"
