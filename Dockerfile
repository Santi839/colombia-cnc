# Dockerfile
FROM php:8.2-apache

# Extensiones mínimas comunes (ajusta si necesitas otras)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
 && docker-php-ext-install pdo pdo_mysql zip \
 && a2enmod rewrite

# DocumentRoot -> /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!Directory /var/www/!Directory /var/www/html/!g' /etc/apache2/apache2.conf

# Copia el proyecto completo (incluye /public)
COPY . /var/www/html

# Permisos (especialmente storage/)
RUN chown -R www-data:www-data /var/www/html && \
    mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache || true && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# Render usa $PORT. Cambiamos el puerto de Apache en runtime.
ENV APACHE_LISTEN_PORT=8080
RUN sed -ri -e 's!Listen 80!Listen ${APACHE_LISTEN_PORT}!g' /etc/apache2/ports.conf
EXPOSE 8080
CMD [ "bash", "-lc", "if [ -n \"$PORT\" ]; then sed -ri -e \"s!Listen ${APACHE_LISTEN_PORT}!Listen ${PORT}!g\" /etc/apache2/ports.conf; fi && apache2-foreground" ]
