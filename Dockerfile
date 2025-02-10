# Docker officil php image
FROM php:8.3-fpm-alpine

# install dependences to use mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install and configure OPcache for performance optimization
RUN docker-php-ext-install opcache

# Add custom OPcache configuration file to the appropriate directory
ADD opcache.ini $PHP_INI_DIR/conf.d/