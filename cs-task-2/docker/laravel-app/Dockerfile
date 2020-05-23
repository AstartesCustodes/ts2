FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip \
    nano \
    net-tools \
    curl 

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install pdo_mysql zip

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY ./src /var/www
COPY --chown=www:www ./src /var/www

WORKDIR /var/www


COPY ./docker/laravel-app/laravel.ini  /usr/local/etc/php/conf.d/local.ini

ARG APP_NAME
ENV APP_NAME=$APP_NAME

ARG REDIS_HOST
ENV REDIS_HOST=$REDIS_HOST

ARG REDIS_PASSWORD
ENV REDIS_PASSWORD=$REDIS_PASSWORD

ARG REDIS_PORT
ENV REDIS_PORT=$REDIS_PORT

ARG REDIS_DB
ENV REDIS_DB=$REDIS_DB

# COPY ./docker/laravel-app/php-fpm.conf /opt/bitnami/php/etc/php-fpm.d/www.conf

EXPOSE 9000

RUN ["sh", "-c", " rm .env && ./templater.sh .env.template >> .env && php artisan key:generate"]

USER www

# CMD ["php-fpm", "-F", "--pid" , "/opt/bitnami/php/tmp/php-fpm.pid", "-c", "/opt/bitnami/php/etc/php-fpm.conf"]

CMD ["sh", "-c", " php-fpm"]

#CMD ["sh", "-c", "php-fpm"]

