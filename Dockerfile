# Gunakan image resmi PHP dengan ekstensi yang dibutuhkan Laravel
FROM php:8.2-fpm

# Install dependencies sistem dan ekstensi PHP penting
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd

# Install Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory di dalam container
WORKDIR /var/www/html

# Salin seluruh file project Laravel ke dalam container
COPY . .

# Install dependency Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose port untuk Railway (Railway akan gunakan PORT=8080)
EXPOSE 8080

# Jalankan Laravel menggunakan port yang diberikan Railway
CMD bash -c "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
