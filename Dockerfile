FROM php:8.1-apache

# Instalar extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activar mod_rewrite para Apache
RUN a2enmod rewrite

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar todos los archivos del proyecto
COPY . /var/www/html/

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias si existe composer.json
RUN if [ -f "composer.json" ]; then composer install --no-interaction --no-dev --optimize-autoloader; fi

# Configurar variables de entorno
RUN touch .env
RUN echo "DB_HOST=postgres" >> .env
RUN echo "DB_NAME=pokemon" >> .env
RUN echo "DB_USER=postgres" >> .env
RUN echo "DB_PASSWORD=postgres" >> .env

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html

# Configurar DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html/public|DocumentRoot /var/www/html|' /etc/apache2/sites-available/000-default.conf

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]