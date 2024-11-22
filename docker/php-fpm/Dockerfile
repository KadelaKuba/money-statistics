FROM php:8.3-fpm-alpine as base

ARG project_root=.
ARG user_gid=1000
ARG user_uid=1000

# Setup non-root user to avoid permission issues later
RUN addgroup --gid ${user_gid} kadela && \
    adduser --disabled-password --uid ${user_uid} --ingroup kadela kadela && \
    mkdir -p /var/www/html && \
    chown -R kadela:kadela /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

# $PHPIZE_DEPS all build deps required for php modules,extensions etc to be installed and enabled (build dep)
# libxml2-dev for compiling dependent modules for SOAP Client (build dep)
# bash because of personal preferrence
# git for computing diffs, composer, etc.
# unzip to ommit composer zip packages corruption
# vim + nano to be able edit some files
# libzip-dev needed by "zip" extension for PHP
RUN apk add --update --no-cache --virtual .build-dependencies \
    $PHPIZE_DEPS \
    linux-headers \
    libxml2-dev \
    && \
    apk add --no-cache \
    bash git nano vim curl zip unzip libzip-dev \
    && \
    docker-php-ext-install zip && \
    docker-php-ext-enable opcache \
    && \
    pecl install xdebug-3.3.2 && \
    docker-php-ext-enable xdebug \
    && \
    apk del .build-dependencies && \
    rm -rf /tmp/* /var/cache/apk/* \
    && \
    echo "source /etc/bash_completion" >> ~/.bashrc

# Switch to non-root user. Rest of dockerfile and at runtime is under this user.
USER kadela

WORKDIR /var/www/html

########################################################################################################################

FROM base as development
# steps only for development image

USER root

COPY ${project_root}/docker/php-fpm/php.development.ini /usr/local/etc/php/php.ini

USER kadela