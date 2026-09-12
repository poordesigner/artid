#!/bin/sh
set -e

mkdir -p \
    /var/www/html/bootstrap/cache \
    /var/www/html/public \
    /var/www/html/storage/app/private \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

cp -R /var/www/html/public-dist/. /var/www/html/public/

chown -R 82:82 /var/www/html/bootstrap/cache /var/www/html/storage

rm -f /var/www/html/bootstrap/cache/*.php

if [ ! -L /var/www/html/public/storage ]; then
    rm -rf /var/www/html/public/storage
    php artisan storage:link || true
fi

chown -R 82:82 /var/www/html/public /var/www/html/bootstrap/cache /var/www/html/storage

exec "$@"
