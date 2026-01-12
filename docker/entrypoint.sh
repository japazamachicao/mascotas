#!/bin/bash
set -e

echo "🚀 Starting Kivets application entrypoint..."

echo "📂 Checking file structure..."
ls -la /var/www/html/public

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
