# Docker officiel PHP avec Alpine
FROM php:8.3-fpm-alpine

# Ajouter les dépôts communautaires pour obtenir les paquets nécessaires
RUN apk update && apk add --no-cache icu-libs icu-dev nodejs npm

# Installer les extensions PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql intl opcache

# Copier Composer depuis l'image officielle (évite de le télécharger à chaque build)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ajouter la configuration personnalisée d'OPcache
ADD opcache.ini $PHP_INI_DIR/conf.d/

# Définir le répertoire de travail
WORKDIR /code

# Copier uniquement composer.json et composer.lock pour éviter de retélécharger les dépendances
COPY composer.json composer.lock ./

# Installer les dépendances sans dev et sans scripts pour accélérer
RUN composer install --no-dev --no-scripts --prefer-dist

# Copier le reste du projet
COPY . .

# Assurer que le dossier de cache a les bonnes permissions
RUN rm -rf var/cache/* && \
    mkdir -p var/cache var/log && \
    chmod -R 777 var/cache var/log && \
    chown -R www-data:www-data var/cache var/log

