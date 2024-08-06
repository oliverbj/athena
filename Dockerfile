# Use the official PHP 8.3 image from serversideup
FROM serversideup/php:8.3-nginx

# Set working directory
WORKDIR /var/www

# Install system dependencies
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

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xsl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Copy environment-specific .env file
ARG ENVIRONMENT=production
COPY .env.production.env

# Change current user to www
USER www-data

# Expose port 8010
EXPOSE 8010

# Start Nginx server
CMD ["start-container"]