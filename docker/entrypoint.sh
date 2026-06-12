#!/bin/bash
set -e

echo "🚀 Starting Kivets application entrypoint..."

# CRITICAL: Change to application directory
cd /var/www/html
echo "📍 Working directory: $(pwd)"
echo "👤 Running as user: $(whoami)"

# Diagnostic: Show what files exist
echo "📋 Directory contents:"
ls -la /var/www/html | head -20

# Copiar .env desde secrets si existe
if [ -f /secrets/.env ]; then
    echo "🔑 Loading environment from /secrets/.env..."
    cp /secrets/.env /var/www/html/.env
else
    echo "⚠️  No .env file found in /secrets/.env"
fi

echo "📂 Checking file structure..."
if [ -d /var/www/html/public ]; then
    echo "✅ Public directory found"
    ls -la /var/www/html/public | head -10
else
    echo "❌ Public directory not found - this may cause issues"
fi

# Verificar artisan
if [ -f /var/www/html/artisan ]; then
    echo "✅ Artisan file found"
else
    echo "❌ Artisan file NOT found - skipping Laravel commands"
    echo "✅ Entrypoint execution complete. Starting supervisord..."
    exec "$@"
fi

# Manejo de almacenamiento
echo "📁 Configuring storage..."
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link || echo "⚠️  Failed to link storage"
fi

# Migraciones (siempre intentar, pero no fallar hard)
echo "🔄 Running migrations..."
php artisan migrate --force --no-interaction || echo "⚠️  Migration failed - check DB connection"

# Configuración y caché
echo "⚡ Optimizing application..."
php artisan config:cache || echo "⚠️  Config cache failed"
php artisan route:cache || echo "⚠️  Route cache failed"
php artisan view:cache || echo "⚠️  View cache failed"

echo "✅ Entrypoint execution complete. Starting supervisord..."

# Ejecutar el comando principal
exec "$@"
