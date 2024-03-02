FROM php:8.2.0-apache
ARG DEBIAN_FRONTEND=noninteractive

######### necesary libraries #########

RUN apt-get update \
    && apt-get install -y sendmail libpng-dev \
    && apt-get install -y libxml2-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y libonig-dev \
    && apt-get install -y libyaml-dev

######### PHP EXTENSIONS #########

RUN pecl install yaml-2.2.3 \
    && docker-php-ext-enable yaml
RUN pecl install -f xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini;
COPY xdebug-vars.ini /xdebug-vars.ini
RUN echo "$(cat /xdebug-vars.ini)" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && rm /xdebug-vars.ini
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
RUN docker-php-ext-install pcntl

######### cleaning #########

RUN rm -rf /var/lib/apt/lists/*

######### enabling rewrite for apache #########

RUN a2enmod rewrite

######### installing composer #########

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html
EXPOSE 9003