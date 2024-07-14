FROM php:8-fpm
RUN apt update -y && apt install -y libzip-dev libpng-dev curl zip unzip
RUN docker-php-ext-install pdo_mysql gd zip opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

