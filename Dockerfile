FROM php:8.1.12-apache

WORKDIR /var/www/html

RUN apt update &&  \
    apt upgrade -y  \
    && apt install -y  git  libzip-dev zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install bcmath

COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY . .

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php  \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && composer update

EXPOSE 80