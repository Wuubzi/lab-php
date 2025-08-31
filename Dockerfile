FROM php:8.2-apache

# Instalar librerías necesarias para extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli pdo pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Copiar configuración de Apache
COPY ./apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Instalar PHPMailer, Cloudinary y FPDF automáticamente
RUN composer require phpmailer/phpmailer cloudinary/cloudinary_php setasign/fpdf \
    --no-interaction --prefer-dist --optimize-autoloader
