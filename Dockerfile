# Docker officiel php image
FROM php:8.3-fpm-alpine

# Ajouter les dépôts communautaires pour obtenir les paquets nécessaires
RUN apk update

# Installer les dépendances nécessaires pour intl
RUN apk add --no-cache icu-libs icu-dev

# Installer les extensions PHP : mysqli, pdo, pdo_mysql, intl et opcache
RUN docker-php-ext-install mysqli pdo pdo_mysql intl opcache

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ajouter la configuration personnalisée d'OPcache
ADD opcache.ini $PHP_INI_DIR/conf.d/

# Définir le répertoire de travail
WORKDIR /code

# Copier tous les fichiers dans le conteneur
COPY . .

# Assurer que le dossier de cache a les bonnes permissions
RUN rm -rf var/cache/* && \
    mkdir -p var/cache var/log && \
    chmod -R 777 var/cache var/log && \
    chown -R www-data:www-data var/cache var/log
