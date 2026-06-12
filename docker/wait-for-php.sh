#!/bin/sh
# Script para esperar a que PHP-FPM esté listo antes de iniciar nginx
set -e

echo "⏳ Waiting for PHP-FPM to be ready..."

max_attempts=30
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if nc -z 127.0.0.1 9000; then
        echo "✅ PHP-FPM is ready on port 9000!"
        # Dar un segundo extra para asegurar que está completamente listo
        sleep 1
        exec nginx -g 'daemon off;'
    fi
    
    attempt=$((attempt + 1))
    echo "   Attempt $attempt/$max_attempts - PHP-FPM not ready yet..."
    sleep 1
done

echo "❌ PHP-FPM failed to start after $max_attempts attempts"
exit 1
