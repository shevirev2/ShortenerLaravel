FROM php:8.3-fpm

ARG UID=1000
ARG GID=1000

RUN groupmod -o -g ${GID} www-data \
 && usermod -o -u ${UID} www-data \
 && mkdir -p /var/www/html \
 && chown -R www-data:www-data /var/www/html

# ------------------------------------------------------------
# 2️⃣ Install system dependencies & PHP extensions
# ------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2 libxml2-dev libzip-dev libssl-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo pdo_mysql mbstring bcmath exif pcntl gd zip xml \
 && docker-php-ext-enable xml \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# ------------------------------------------------------------
# 3️⃣ Copy Composer
# ------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ------------------------------------------------------------
# 4️⃣ Create working directory & set permissions
# ------------------------------------------------------------
WORKDIR /var/www/html/app

# Ensure Laravel writable directories exist and are writable
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/app \
    && chmod -R 775 storage bootstrap/cache

# ------------------------------------------------------------
# 5️⃣ Install Redis extension
# ------------------------------------------------------------
RUN pecl install redis && docker-php-ext-enable redis

# ------------------------------------------------------------
# 6️⃣ Default command
# ------------------------------------------------------------
CMD ["php-fpm"]

USER www-data
