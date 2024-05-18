FROM php:8.1-apache
# Instala las dependencias necesarias para compilar extensiones
RUN apt-get update && apt-get install -y libpq-dev libjpeg-dev libpng-dev libfreetype6-dev
# Habilita la extensión PDO para MySQL
RUN docker-php-ext-install pdo pdo_mysql
COPY public_html/ /var/www/html/
EXPOSE 80