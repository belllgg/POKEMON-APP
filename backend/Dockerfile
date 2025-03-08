FROM php:8.1-apache

# Instalar extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activar mod_rewrite para Apache
RUN a2enmod rewrite

# 🔥 SOLUCIÓN: Copiar `.htaccess` explícitamente a la raíz
COPY .htaccess /var/www/html/.htaccess

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . /var/www/html/

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Crear archivo .env con credenciales
RUN touch .env
RUN echo "DB_HOST=postgres" >> .env
RUN echo "DB_NAME=pokemon" >> .env
RUN echo "DB_USER=postgres" >> .env
RUN echo "DB_PASSWORD=postgres" >> .env

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html

# 🔥 SOLUCIÓN: Asegurar que Apache use el DocumentRoot correcto
RUN sed -i 's|DocumentRoot /var/www/html/public|DocumentRoot /var/www/html|' /etc/apache2/sites-available/000-default.conf

# 🔥 SOLUCIÓN: Reiniciar Apache después de modificar configuraciones
RUN service apache2 restart

# Exponer puerto 80
EXPOSE 80

# 🔥 SOLUCIÓN: Asegurar que Apache se mantenga ejecutándose
CMD ["apache2-foreground"]
