# Dockerfile
FROM php:8.2-apache

# 1) Extensiones necesarias (pgsql/pdo_pgsql, zip, intl si hace falta)
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libicu-dev \
 && docker-php-ext-install pdo_pgsql pgsql zip intl \
 && rm -rf /var/lib/apt/lists/*

# 2) Habilitar mod_rewrite (URLs amigables)
RUN a2enmod rewrite

# 3) Ajustar DocumentRoot a /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g' \
    /etc/apache2/apache2.conf

# 4) Copiar proyecto
COPY . /var/www/html/

# 5) Permisos mínimos
RUN chown -R www-data:www-data /var/www/html

# 6) Exponer el puerto estándar
EXPOSE 80
