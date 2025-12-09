FROM php:8.5-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

EXPOSE 9000

CMD ["php-fpm"]