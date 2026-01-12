# Dockerfile para Kivets - Laravel en Cloud Run
FROM php:8.2-fpm-alpine AS base

# Instalar extensiones PHP necesarias
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        zip \
        mbstring \
        intl \
        opcache \
        bcmath

# Configurar opcache para producción
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer
COPY composer.json composer.lock ./

# Instalar dependencias de producción
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar el resto de la aplicación
COPY . .

# Generar autoloader optimizado
RUN composer dump-autoload --optimize --classmap-authoritative

# Copiar script de entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Configurar permisos, finales de línea y directorios necesarios
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && apk add --no-cache dos2unix bash \
    && dos2unix /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /run/nginx \
    && mkdir -p /var/log/supervisor

# Copiar configuración de nginx
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copiar configuración de supervisord
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Exponer puerto
EXPOSE 8080



# Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
