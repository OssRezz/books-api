# Imagen base con PHP y Apache para Lendify
FROM php:8.2-apache

# Configurar zona horaria
ENV TZ=America/Bogota
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Instalar extensiones necesarias
RUN apt update && apt install -y \
    zip unzip zlib1g-dev libpng-dev libzip-dev libxml2-dev \
    cron && \
    docker-php-ext-install pdo pdo_mysql gd soap zip intl

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configuración de Apache para producción
COPY docker/apache-prod.conf /etc/apache2/sites-available/000-default.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar proyecto Laravel
COPY . .

# Instalar Composer sin dependencias de desarrollo
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Copiar .env
COPY .env /var/www/html/.env

# Comandos Laravel post-instalación
RUN php artisan key:generate && php artisan storage:link
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 80

CMD ["apache2-foreground"]