FROM php:8.4-fpm-alpine3.21

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    git \
    icu-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    nodejs \
    npm \
    oniguruma-dev \
    postgresql-dev \
    su-exec \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath exif gd intl mbstring opcache pcntl pdo_pgsql pgsql zip

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/artid.ini
COPY docker/php/entrypoint.sh /usr/local/bin/artid-entrypoint
RUN chmod +x /usr/local/bin/artid-entrypoint

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --no-progress

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .

RUN composer dump-autoload --optimize --no-scripts

RUN npm run build

RUN cp -R public /var/www/html/public-dist

RUN mkdir -p bootstrap/cache storage/app/private storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chown -R 82:82 bootstrap/cache storage

ENTRYPOINT ["artid-entrypoint"]
CMD ["php-fpm"]
