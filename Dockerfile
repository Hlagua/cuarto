# 1. Base oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# 2. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    default-jre \
    fontconfig \
    nodejs \
    npm \
    git \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# 3. Instalar la extensión mysqli (y PDO por si acaso)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 4. Instalar Composer (copiándolo de la imagen oficial)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar Apache para que la raíz sea /app (como lo espera Railway)
ENV APACHE_DOCUMENT_ROOT=/app
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Habilitar mod_rewrite (esencial para tu index.php?accion=...)
RUN a2enmod rewrite

# 7. Copiar tu código al contenedor
WORKDIR /app
COPY . /app

# 8. LA SOLUCIÓN AL PUERTO Y A LOS PERMISOS
# Movemos la configuración del puerto aquí para que se evalúe correctamente al arrancar
# 8. LA SOLUCIÓN AL PUERTO Y A LOS PERMISOS
# Ahora forzamos el puerto 8080 explícitamente en la configuración
RUN echo '#!/bin/bash\n\
a2dismod mpm_event mpm_worker\n\
a2enmod mpm_prefork\n\
sed -i "s/Listen 80/Listen 8080/g" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:8080>/g" /etc/apache2/sites-available/000-default.conf\n\
composer install --ignore-platform-reqs --no-interaction\n\
mkdir -p /app/imagenes/productos\n\
chown -R www-data:www-data /app/imagenes/productos\n\
chmod -R 775 /app/imagenes/productos\n\
exec apache2-foreground' > /start.sh

# 9. Dar permisos de ejecución al script
RUN chmod +x /start.sh

# 10. Comando final
CMD ["/start.sh"]