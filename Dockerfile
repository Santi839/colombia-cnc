# Dockerfile (Plan B: Reglas de reescritura directas)
# Esto elimina la necesidad de un archivo .htaccess

FROM php:8.2-apache

# 1) Extensiones
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libicu-dev \
 && docker-php-ext-install pdo_pgsql pgsql zip intl \
 && rm -rf /var/lib/apt/lists/*

# 2) Habilitar mod_rewrite
RUN a2enmod rewrite

# 3) Ajustar DocumentRoot y AÑADIR REGLAS DE REESCRITURA
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Aquí inyectamos las reglas de reescritura en un archivo de configuración.
# Esto es más eficiente y robusto que usar .htaccess.
RUN { \
    echo '<Directory ${APACHE_DOCUMENT_ROOT}>'; \
    echo '    Options Indexes FollowSymLinks'; \
    echo '    AllowOverride None'; \
    echo '    Require all granted'; \
    echo '    '; \
    echo '    RewriteEngine On'; \
    echo '    RewriteBase /'; \
    echo '    RewriteCond %{REQUEST_FILENAME} !-f'; \
    echo '    RewriteCond %{REQUEST_FILENAME} !-d'; \
    echo '    RewriteRule . index.php [L]'; \
    echo '</Directory>'; \
} > /etc/apache2/conf-available/public-directory.conf

# Reemplazar la configuración de DocumentRoot en el archivo principal
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf

# Reemplazar la configuración del directorio raíz en apache2.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/../!g' \
    /etc/apache2/apache2.conf

# Habilitar nuestra nueva configuración de directorio
RUN a2enconf public-directory

# 4) Copiar proyecto
COPY . /var/www/html/

# 5) Permisos
RUN chown -R www-data:www-data /var/www/html

# 6) Puerto
EXPOSE 80
