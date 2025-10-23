# Dockerfile (raíz del repo)
FROM php:8.2-apache

# Habilita mod_rewrite para rutas amigables
RUN a2enmod rewrite

# Copia TODO el proyecto al docroot de Apache
COPY . /var/www/html/

# (Opcional pero recomendado) permisos
RUN chown -R www-data:www-data /var/www/html
