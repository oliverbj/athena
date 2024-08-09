############################################
# Base Image
############################################
FROM serversideup/php:8.3-fpm-nginx-alpine AS base

# Switch to root before installing our PHP extensions
USER root
RUN install-php-extensions bcmath gd

############################################
# Development Image
############################################
FROM base AS development

ARG USER_ID
ARG GROUP_ID

USER root
RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID && \
    docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service nginx
    
RUN install-php-extensions intl

USER www-data

############################################
# CI image
############################################
FROM base AS ci

USER root
RUN echo "user = www-data" >> /usr/local/etc/php-fpm.d/docker-php-serversideup-pool.conf && \
    echo "group = www-data" >> /usr/local/etc/php-fpm.d/docker-php-serversideup-pool.conf

############################################
# Production Image
############################################
FROM base AS deploy

# Copy application files
#Add an output comment so we can see where we are
RUN echo "Copying application files"
COPY --chown=www-data:www-data . /var/www/html
RUN echo "the files in the main directory"
RUN ls -la .
#list the files in the directory
RUN echo "the files in the Models directory"
RUN ls -la /var/www/html/app/Models

# Optionally, you can provide a custom nginx.conf if needed
# COPY nginx.conf /etc/nginx/nginx.conf

USER www-data
