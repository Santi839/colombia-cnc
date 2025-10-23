FROM php:8.2-apache

# Extensiones mínimas frecuentes (ajústalas según tu app)
RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev libxml2-dev unzip git \
 && docker-php-ext-install pdo pdo_mysql zip \
 && a2enmod rewrite

# Laravel sirve desde /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Actualiza VirtualHost y Apache para usar /public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!/var/www/html/!g' /etc/apache2/apache2.conf

# Copia proyecto completo (incluye public/)
COPY . /var/www/html

# Permisos de storage/bootstrap
RUN chown -R www-data:www-data /var/www/html && \
    mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto para Render
ENV APACHE_LISTEN_PORT=8080
RUN sed -ri -e 's!Listen 80!Listen ${APACHE_LISTEN_PORT}!g' /etc/apache2/ports.conf
EXPOSE 8080

CMD [ "bash", "-lc", "if [ -n \"$PORT\" ]; then sed -ri -e \"s!Listen ${APACHE_LISTEN_PORT}!Listen ${PORT}!g\" /etc/apache2/ports.conf; fi && apache2-foreground" ]
