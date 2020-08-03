FROM php:7.1-fpm

# libpq-dev is needed for missing includes for pdo_pgsql
RUN apt-get update && apt-get install -y libmcrypt-dev zip unzip \
    libmagickwand-dev --no-install-recommends \
    git \
    libzip-dev \
    && docker-php-ext-configure mysqli -with-pgsql=/usr/local/mysql \
    && docker-php-ext-install pdo pdo_mysql mysqli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
