FROM php:8.2-apache

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chmod -R 777 /var/www/html/data || true

EXPOSE 80