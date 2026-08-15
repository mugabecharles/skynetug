#!/bin/bash
# SkyNetug Deployment Script
# Run this on your live server after uploading files

echo "=== SkyNetug Deployment ==="

# 1. Install dependencies (no dev packages)
echo "Installing dependencies..."
composer install --optimize-autoloader --no-dev

# 2. Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 3. Run migrations
echo "Running migrations..."
php artisan migrate --force

# 4. Cache for production (faster)
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Create storage symlink
echo "Creating storage link..."
php artisan storage:link

# 6. Set permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env

echo ""
echo "=== Deployment Complete ==="
echo "Site is live!"
