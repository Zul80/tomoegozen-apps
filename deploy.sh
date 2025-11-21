#!/bin/bash

# Script Deployment Tomoe Gozen
# Usage: bash deploy.sh

echo "🚀 Starting Tomoe Gozen Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  File .env tidak ditemukan!${NC}"
    if [ -f .env.example ]; then
        echo "📋 Copying .env.example to .env..."
        cp .env.example .env
        echo -e "${GREEN}✅ File .env created${NC}"
    else
        echo -e "${RED}❌ File .env.example juga tidak ditemukan!${NC}"
        exit 1
    fi
fi

# Check if APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo -e "${YELLOW}⚠️  APP_KEY belum di-set!${NC}"
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
    echo -e "${GREEN}✅ APP_KEY generated${NC}"
else
    echo -e "${GREEN}✅ APP_KEY sudah ada${NC}"
fi

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node dependencies..."
npm ci

echo "🏗️  Building assets..."
npm run build

# Storage link
if [ ! -L public/storage ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
    echo -e "${GREEN}✅ Storage link created${NC}"
fi

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Clear and cache config
echo "⚡ Optimizing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (Linux/Unix only)
if [ "$(uname)" != "MINGW"* ] && [ "$(uname)" != "MSYS"* ]; then
    echo "🔐 Setting permissions..."
    chmod -R 775 storage bootstrap/cache
    echo -e "${GREEN}✅ Permissions set${NC}"
fi

echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo ""
echo "📝 Next steps:"
echo "   1. Pastikan database sudah dikonfigurasi di .env"
echo "   2. Jika perlu seed data: php artisan db:seed --class=ShopSeeder"
echo "   3. Pastikan web server mengarah ke folder root (bukan public/)"
echo "   4. Test akses website di browser"

