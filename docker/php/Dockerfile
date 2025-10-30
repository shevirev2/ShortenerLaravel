FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libssl-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath exif pcntl gd zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/app

RUN chown -R www-data:www-data /var/www/html/app

# Ensure Laravel can write to storage & cache on startup
RUN mkdir -p /var/www/html/app/storage /var/www/html/app/bootstrap/cache \
    && chmod -R 777 /var/www/html/app/storage /var/www/html/app/bootstrap/cache

CMD ["php-fpm"]