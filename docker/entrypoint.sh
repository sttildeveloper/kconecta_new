#!/bin/sh
set -e

cd /var/www/html

mkdir -p public/img/uploads public/video/uploads public/img/photo_profile storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

if [ ! -f vendor/autoload.php ]; then
    composer install --prefer-dist --no-interaction --no-progress --optimize-autoloader
fi

# The project directory is bind-mounted from Windows in local Docker. Recursive
# ownership changes on that mount are very slow and are not required there.
chmod -R ug+rwX storage/framework storage/logs bootstrap/cache public/img/uploads public/video/uploads public/img/photo_profile
umask 0002

php artisan optimize:clear

if [ "${APP_ENV}" != "local" ] && [ "${APP_ENV}" != "testing" ]; then
    php artisan config:cache
fi

exec apache2-foreground
