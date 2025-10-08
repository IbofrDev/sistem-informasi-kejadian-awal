# Gunakan image resmi PHP + extensions
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy seluruh file project
COPY . .

# Install dependencies Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate app key otomatis jika belum ada
RUN php artisan key:generate --force || true

# Expose port
EXPOSE 8080

# Jalankan Laravel di port 10000
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
