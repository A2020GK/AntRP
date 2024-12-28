# syntax=docker/dockerfile:1

FROM composer:lts AS deps

WORKDIR /app
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:8.3.6-apache AS final

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_SERVER_ADMIN a2020gk@yandex.ru

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's!webmaster@localhost!${APACHE_SERVER_ADMIN}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

RUN apt update && apt install -y supervisor
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=deps app/vendor/ /var/www/html/vendor
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
USER www-data
CMD ["sh", "-c", "apachectl -D FOREGROUND & /usr/bin/supervisord"]