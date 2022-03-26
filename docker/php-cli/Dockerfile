FROM php:8.0-cli-alpine

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
