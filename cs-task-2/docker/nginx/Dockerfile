FROM nginx

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip \
    nano \
    curl 

COPY ./src /var/www

COPY ./docker/nginx/conf.d/fpm.conf  /etc/nginx/conf.d/fpm.conf

WORKDIR /var/www

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]