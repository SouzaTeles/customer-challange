FROM php:8.5-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache bash

WORKDIR /var/www/backend

RUN echo "alias t='vendor/bin/phpunit --do-not-cache-result'" >> /root/.bashrc

EXPOSE 9000

CMD ["php-fpm"]