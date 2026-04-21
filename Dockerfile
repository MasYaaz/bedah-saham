FROM php:8.2-fpm-alpine

# 1. Install dependensi sistem dan ekstensi PHP
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl gd zip mysqli pdo_mysql mbstring

# 2. Setup folder kerja
WORKDIR /var/www/html

# 3. Copy file konfigurasi Nginx
COPY nginx.conf /etc/nginx/http.d/default.conf

# 4. Copy project & Atur Permission
COPY . .
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# 5. Buat script starter (agar PHP-FPM dan Nginx jalan bareng)
RUN echo "#!/bin/sh" > /start.sh && \
    echo "php-fpm -D" >> /start.sh && \
    echo "nginx -g 'daemon off;'" >> /start.sh && \
    chmod +x /start.sh

EXPOSE 80

# Jalankan script starter
CMD ["/start.sh"]