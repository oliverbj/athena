FROM php:8.3-fpm


#Install system dependencies
RUN apt-get update && apt-get install -y \
git \
curl \
libpq-dev \
libpng-dev \
libonig-dev \
libxml2-dev \
zip \
unzip

#Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

#Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

#Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/user user RUN mkdir -p /home/user/.composer && chown -R user:user /home/user

#Set working directory
WORKDIR /var/www

USER user