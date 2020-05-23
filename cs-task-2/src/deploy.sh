#!/bin/bash

rm .env
sh templater.sh .env.template >> .env 
php artisan key:generate
php-fpm