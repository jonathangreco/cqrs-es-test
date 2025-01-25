FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git  \
    unzip \
    wget \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    && docker-php-ext-install intl pdo_mysql mbstring zip opcache dom \
    && docker-php-ext-enable intl pdo_mysql mbstring zip opcache dom

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

RUN wget http://pear.php.net/go-pear.phar
RUN php go-pear.phar

RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

ENV APP_ENV=dev
ENV APP_NAME=Danim
ENV BROADCAST_DRIVER=log
ENV LOG_CHANNEL=stderr

# Install Symfony CLI to require some UX icons:
RUN apt purge -y --autoremove curl
RUN ldconfig
RUN apt install -y curl
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN export PATH="$HOME/.symfony5/bin:$PATH"

RUN chmod +w /usr/local/bin \
    && wget https://getcomposer.org/installer -O composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

WORKDIR /var/www/danim

RUN chown -R www-data:www-data /var/www
RUN chmod -R 777 /var/www/danim/
