#!/usr/bin/env bash

# Renderから渡される $PORT を Nginx の設定に反映
envsubst '$PORT' < /etc/nginx/sites-available/default > /etc/nginx/sites-enabled/default

echo "Running migrations..."
php artisan migrate --force

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
nginx -g "daemon off;"