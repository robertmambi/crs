#!/bin/bash

cd /var/www

# Install Laravel if not exists
if [ ! -f artisan ]; then
    composer create-project laravel/laravel:^12.0 .
fi

# Copy env if not exists
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Set DB config
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i 's/# DB_HOST=.*/DB_HOST=db/' .env
sed -i 's/# DB_PORT=.*/DB_PORT=3306/' .env
sed -i 's/# DB_DATABASE=.*/DB_DATABASE=car_rental/' .env
sed -i 's/# DB_USERNAME=.*/DB_USERNAME=root/' .env
sed -i 's/# DB_PASSWORD=.*/DB_PASSWORD=root/' .env

sed -i 's/CACHE_STORE=.*/CACHE_STORE=file/' .env
sed -i 's/SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env

# Install dependencies
composer install

# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Generate key if not exists
php artisan key:generate

# Clear cache
php artisan config:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm