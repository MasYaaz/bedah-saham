FROM php:8.3-apache

# Instal dependensi sistem dan ekstensi PHP yang diwajibkan oleh CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo_mysql \
    && a2enmod rewrite

# Tetapkan folder kerja di dalam kontainer
WORKDIR /var/www/html
