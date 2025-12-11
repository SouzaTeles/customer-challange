FROM php:8.5-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache bash linux-headers $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS linux-headers

WORKDIR /var/www/backend

RUN echo "alias t='vendor/bin/phpunit --do-not-cache-result'" >> /root/.bashrc

EXPOSE 9000 9003

CMD ["php-fpm"]