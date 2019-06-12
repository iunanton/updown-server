FROM php:7.2.19-apache

MAINTAINER Anton Iun

COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql
RUN chown -R www-data:www-data /srv/app \
    && a2enmod rewrite
