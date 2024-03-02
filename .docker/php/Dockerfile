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