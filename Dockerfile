FROM php:8.2-apache

# 1. Install ekstensi
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl gd zip mysqli pdo_mysql

# 2. BERSIHKAN & PAKSA: Hapus semua file mod-enabled MPM 
# dan pastikan tidak ada yang ter-load otomatis
RUN find /etc/apache2/mods-enabled -name "mpm_*" -delete

# 3. Masukkan file fix kita ke Apache
COPY mpm_fix.conf /etc/apache2/conf-available/mpm_fix.conf
RUN a2enconf mpm_fix

# 4. Aktifkan mod_rewrite
RUN a2enmod rewrite

# 5. Set Document Root (tetap sama)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Copy project & Permissions
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/writable && chmod -R 775 /var/www/html/writable

EXPOSE 80

CMD ["apache2-foreground"]