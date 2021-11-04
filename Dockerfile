# Alpine3.13 needed or xdebug install fails
FROM php:8.1.0RC5-alpine3.13 AS env
# Install core dependencies
RUN apk add --no-cache $PHPIZE_DEPS
RUN pecl install apcu && docker-php-ext-enable apcu
# Install PHP extensions
# For both build performance and security best practices, you should install
# only the bare minimum set of extensions needed for your application to run.
#
# The list of available options is available by running `docker-php-ext-install`
# with no arguments inside a running container.
RUN docker-php-ext-install opcache pdo_mysql

# Basic env settings
ENV PORT=6543
# Get out of FS root
WORKDIR /app
# Set up runtime user
RUN addgroup php-users && adduser -D -G php-users php


FROM env AS env-with-xdebug
# XDebug settings for coverage
RUN pecl install xdebug \
  && echo 'zend_extension=xdebug.so' > ${PHP_INI_DIR}/conf.d/xdebug.ini


FROM composer:2 AS dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev


FROM dependencies AS dev-dependencies
RUN composer install


FROM env AS server
COPY --chown=php:php-users --from=dependencies /app/vendor ./vendor
COPY --chown=php:php-users . .
# This runs the built-in web server as an initial starting point, as to avoid
# making strong opinions about how best to deploy. For anything other than local
# development, you'll want to run a "real" server.
#
# Some options include:
# - Apache + mod_php
# - PHP-FPM (+ Nginx)
# - PHP-PM: https://github.com/php-pm/php-pm
# - Phalcon: https://phalcon.io/en-us
# - ReactPHP: https://reactphp.org
# - RoadRunner: https://roadrunner.dev
# - Swoole: https://www.swoole.co.uk
CMD php -S 0.0.0.0:$PORT public/index.php


FROM env-with-xdebug AS testenv
ENV XDEBUG_MODE=coverage
COPY --chown=php:php-users --from=dev-dependencies /app/vendor ./vendor
COPY --chown=php:php-users . .
# No command - one will be provided by the test runners
