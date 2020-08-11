FROM php:7.3-fpm-alpine


RUN docker-php-ext-install pdo pdo_mysql

RUN chown -R www-data:www-data /var/www

RUN chmod 777 -R /var/www