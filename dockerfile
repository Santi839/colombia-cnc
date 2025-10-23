FROM php:8.2-apache
RUN a2enmod rewrite

# Copia solo la carpeta public al docroot
COPY public/ /var/www/html/
# Si tienes assets fuera de public, copia lo necesario:
# COPY assets/ /var/www/html/assets/

RUN chown -R www-data:www-data /var/www/html
