if [ ! -f "vendor/autoload.php" ]; then
  composer install
fi

if [ ! -f ".env" ]; then
   cp .env.example .env
fi

php artisan migrate
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan serve --port=8010 --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"