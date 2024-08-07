FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && \
    apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nano \
    libzip-dev \
    libxslt-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xsl zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

COPY --chown=www-data:www-data . /var/www

USER www-data
