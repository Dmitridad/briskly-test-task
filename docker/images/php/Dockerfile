FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        curl \
        libpq-dev \
        git \
        zlib1g-dev \
        nano \
    && docker-php-ext-install pdo pdo_pgsql zip


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/xls-exchanger