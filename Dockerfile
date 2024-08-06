FROM php:8.2-fpm

ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    nginx \
    build-essential \
    openssl

# Install PHP extensions
RUN docker-php-ext-install gd pdo pdo_mysql sockets

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# If you need to fix ssl
COPY ./openssl.cnf /etc/ssl/openssl.cnf
# If you need add extension create an php.ini file
#COPY ./php.ini /usr/local/etc/php/php.ini
COPY composer.json composer.lock ./

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer dependencies
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction

# Copy application files
COPY . .

# Set ownership
RUN chown -R $uid:$uid /var/www

# Copy supervisor configuration
COPY ./supervisord.conf /etc/supervisord.conf

# Run supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]