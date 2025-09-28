FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libgmp-dev libicu-dev pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql bcmath zip gd intl calendar \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app source
COPY ${PROJECT_ROOT}/ .

# Permissions
RUN mkdir -p storage/logs storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data . \
    && chmod -R 775 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose Apache
EXPOSE 80

CMD ["apache2-foreground"]
