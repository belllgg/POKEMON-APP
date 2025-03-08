# Etapa de construcción del frontend
FROM node:16-alpine AS frontend-builder
# Configurar directorio de trabajo
WORKDIR /app
# Copiar solo el package.json primero (sin package-lock.json)
COPY frontend/package.json ./
# Instalar dependencias sin requerer package-lock.json
RUN npm install
# Copiar el código fuente
COPY frontend/ ./
# Construir la aplicación
RUN npm run build

# Etapa final con el backend
FROM php:8.1-apache
# Instalar extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activar mod_rewrite para Apache
RUN a2enmod rewrite

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del backend
COPY backend/ /var/www/html/

# Copiar .htaccess explícitamente a la raíz
COPY backend/.htaccess /var/www/html/.htaccess

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

# Copiar la build del frontend
COPY --from=frontend-builder /app/build/ /var/www/html/public/

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html

# Asegurar que Apache use el DocumentRoot correcto
RUN sed -i 's|DocumentRoot /var/www/html/public|DocumentRoot /var/www/html|' /etc/apache2/sites-available/000-default.conf

# Reiniciar Apache después de modificar configuraciones
RUN service apache2 restart

# Exponer puerto 80
EXPOSE 80

# Asegurar que Apache se mantenga ejecutándose
CMD ["apache2-foreground"]