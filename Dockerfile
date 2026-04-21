FROM php:8.2-apache

# 1. Install ekstensi yang dibutuhkan CI4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl gd zip mysqli pdo_mysql

# 2. FIX MPM ERROR: Matikan mpm_event dan pastikan mpm_prefork yang jalan
# Ini untuk mengatasi error "More than one MPM loaded"
RUN a2dismod mpm_event || true && a2enmod mpm_prefork

# 3. Aktifkan mod_rewrite untuk Apache
RUN a2enmod rewrite

# 4. Set Document Root ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy project
COPY . /var/www/html/

# 6. Set permission untuk folder writable agar CI4 bisa nulis log/cache
RUN chown -R www-data:www-data /var/www/html/writable && chmod -R 775 /var/www/html/writable

# 7. Railway menggunakan port dinamis, pastikan Apache mendengarkan port yang benar
# Kita biarkan Apache tetap di port 80, Railway akan melakukan mapping otomatis
EXPOSE 80

RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:${PORT}/' /etc/apache2/sites-available/000-default.conf

# Jalankan Apache di foreground
CMD ["apache2-foreground"]