FROM php:8.1-fpm

# pcntl and posix are for psalm
RUN apt-get update && apt-get install -y \
 && apt-get install -y --no-install-recommends libzip-dev unzip \
 && docker-php-ext-install -j$(nproc) pdo_mysql pcntl posix zip \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /opt/app

COPY php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY php-fpm/php-fpm.d /usr/local/etc/php-fpm.d
COPY php/php.ini /usr/local/etc/php.ini
