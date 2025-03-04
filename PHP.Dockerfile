FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    zip
# RUN pecl install xdebug && docker-php-ext-enable xdebug