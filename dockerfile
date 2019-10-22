FROM php:7.3.10-apache

RUN apt-get update  && \
    apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    libjpeg62-turbo-dev \
    wget \
    && docker-php-ext-configure gd \
             --with-jpeg-dir=/usr/include/ \
             --with-png-dir=/usr/include/ \
             --with-freetype-dir=/usr/include/freetype2 \
    && docker-php-ext-install gd \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql \