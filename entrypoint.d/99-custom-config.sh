#!/bin/sh
# Ensure the script is executed at the start
echo "Starting custom configuration..."

# Ensure PHP-FPM listens on port 9000
sed -i 's/listen = 127.0.0.1:8020/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf
sed -i 's/fastcgi_pass 127.0.0.1:8020/fastcgi_pass 127.0.0.1:9000/' /etc/nginx/http.d/default.conf

# Set correct permissions for Laravel directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create a .env file from stack.env if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/stack.env /var/www/html/.env
    echo ".env file created from stack.env"
else
    echo ".env file already exists"
fi

echo "Custom configuration completed!"