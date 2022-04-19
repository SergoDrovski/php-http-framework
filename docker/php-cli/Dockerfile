FROM php:8.0-cli-alpine

RUN apk update && apk add autoconf g++ make \
    && pecl install xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY php/conf.d /usr/local/ect/php/conf.d
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
