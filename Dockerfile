ARG PHP_VERSION=8.1.29
ARG COMPOSER_VERSION=2.8.11

FROM php:${PHP_VERSION}-apache AS web
ARG COMPOSER_VERSION

# Install system dependencies
RUN echo "Acquire::http::Pipeline-Depth 0; \n Acquire::http::No-Cache true;" > /etc/apt/apt.conf.d/99fixbadproxy && \
    apt-get update && \
    apt-get install -y libyaml-dev
# Install built-in PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql
# Allow Apache to rewrite URLs via .htaccess 
RUN a2enmod rewrite
# Set timezone
RUN sed 's#;date.timezone =#date.timezone = America/Los_Angeles#' /usr/local/etc/php/php.ini-production \
> /usr/local/etc/php/php.ini

# Install Composer
COPY scripts/install-composer.sh /tmp/install-composer.sh
RUN sh /tmp/install-composer.sh --version=${COMPOSER_VERSION} && \
    rm /tmp/install-composer.sh

WORKDIR /var/www/html

COPY ./ /var/www/html

EXPOSE 80