FROM php:fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies, PHP extensions, Node.js, and npm
RUN apk add --no-cache \
    build-base autoconf g++ make linux-headers libpng-dev libjpeg-turbo-dev freetype-dev \
    zip vim unzip git curl libzip-dev sqlite-dev icu-dev nodejs npm \
    aspell aspell-en \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_sqlite zip exif pcntl gd intl \
    && npm install -g npm@latest \
    && apk del build-base autoconf g++ make linux-headers

# Configure Xdebug
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini  \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini  \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Add and configure www user for Laravel application
RUN addgroup -g 1000 www \
    && adduser -u 1000 -G www -s /bin/sh -D www

# Ensure proper permissions for application code
COPY . /var/www
RUN chown -R www:www /var/www

# Switch to www user
USER www
