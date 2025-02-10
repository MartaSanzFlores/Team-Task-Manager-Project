# Docker officil php image
FROM php:8.3-fpm-alpine

# install dependences to use mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install and configure OPcache for performance optimization
RUN docker-php-ext-install opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add custom OPcache configuration file to the appropriate directory
ADD opcache.ini $PHP_INI_DIR/conf.d/

# Set the working directory
WORKDIR /code

# Copy all files
COPY . .