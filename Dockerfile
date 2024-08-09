FROM serversideup/php:8.3-fpm-nginx-alpine AS base

USER root
RUN install-php-extensions bcmath gd intl

# Ensure PHP-FPM listens on port 9000
RUN sed -i 's/listen = 127.0.0.1:8020/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/fastcgi_pass 127.0.0.1:8020/fastcgi_pass 127.0.0.1:9000/' /etc/nginx/http.d/default.conf

# Set up logging
RUN touch /var/log/php-fpm.log && chown www-data:www-data /var/log/php-fpm.log \
    && echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_value[error_log] = /var/log/php-fpm.log" >> /usr/local/etc/php-fpm.d/www.conf

FROM base AS development

ARG USER_ID
ARG GROUP_ID

USER root
RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID  && \
    docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service nginx

USER www-data

FROM base AS deploy

COPY --chown=www-data:www-data . /var/www/html

# Copy .env.example to .env if .env doesn't exist
RUN if [ ! -f /var/www/html/.env ]; then cp /var/www/html/.env.example /var/www/html/.env; fi

# Set correct permissions
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

# Install dependencies and optimize
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate --force

# Cache configuration
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Create a script to run migrations and start the server
RUN echo '#!/bin/sh' > /var/www/html/start.sh && \
    echo 'php artisan migrate --force' >> /var/www/html/start.sh && \
    echo 'php-fpm' >> /var/www/html/start.sh && \
    chmod +x /var/www/html/start.sh

CMD ["/var/www/html/start.sh"]