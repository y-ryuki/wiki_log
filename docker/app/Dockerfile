FROM php:8.2-apache
WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql

# composer のインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# ファイルのコピー

COPY ./docker/app/php.ini /usr/local/etc/app/php.ini
