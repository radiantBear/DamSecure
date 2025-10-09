ARG PHP_VERSION=8.1.29
ARG COMPOSER_VERSION=2.8.11


FROM composer/composer:${COMPOSER_VERSION}-bin AS composer
# This stage is just needed to allow templating the version in the image name


FROM php:${PHP_VERSION}-apache AS web
WORKDIR /var/www/html
EXPOSE 80

# Install system dependencies
RUN echo "Acquire::http::Pipeline-Depth 0; \n Acquire::http::No-Cache true;" > /etc/apt/apt.conf.d/99fixbadproxy && \
    apt-get update && \
    apt-get install -y libyaml-dev zip unzip
# Install built-in PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql
# Allow Apache to rewrite URLs via .htaccess 
RUN a2enmod rewrite
# Set timezone
RUN sed 's#;date.timezone =#date.timezone = America/Los_Angeles#' /usr/local/etc/php/php.ini-production \
> /usr/local/etc/php/php.ini

# Install Composer
COPY --from=composer /composer /usr/bin/composer

# Install application dependencies. Many files needed for post-install hooks, so just copy it all
COPY . .
RUN composer install

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
