FROM php:8.1.29-apache

# Install system dependencies
RUN apt-get update && apt-get install -y libyaml-dev
# Install built-in PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql
# Allow Apache to rewrite URLs via .htaccess 
RUN a2enmod rewrite
# Set timezone
RUN sed 's#;date.timezone =#date.timezone = America/Los_Angeles#' /usr/local/etc/php/php.ini-production \
        > /usr/local/etc/php/php.ini

WORKDIR /var/www/html

EXPOSE 80