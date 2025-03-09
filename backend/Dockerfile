FROM php:8.3-apache

# Instalar extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar archivos del proyecto
WORKDIR /var/www/html
COPY . /var/www/html/

# 🔥 SOLUCIÓN: Copiar `.htaccess` explícitamente a la raíz
COPY .htaccess /var/www/html/.htaccess

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🔥 SOLUCIÓN: Crear el directorio faltante antes de ejecutar composer install
RUN mkdir -p /var/www/html/vendor/symfony/polyfill-php80/Resources/stubs

# Instalar dependencias sin verificar requisitos de plataforma
RUN composer install --no-interaction --no-dev --optimize-autoloader --ignore-platform-reqs

# 🔥 SOLUCIÓN: Ajustar permisos para evitar problemas de acceso
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 🔥 SOLUCIÓN: Asegurar que Apache use el DocumentRoot correcto
RUN sed -i 's|DocumentRoot /var/www/html/public|DocumentRoot /var/www/html|' /etc/apache2/sites-available/000-default.conf

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
