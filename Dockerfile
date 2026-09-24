FROM php:8.4-cli

WORKDIR /app

RUN apt-get update && apt-get install -y zip unzip git \
    && docker-php-ext-install pdo pdo_sqlite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && touch database/database.sqlite \
    && php artisan key:generate
