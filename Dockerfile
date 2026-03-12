FROM php:8.2-apache

# Install mysqli extension for PHP
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite (optional but recommended for PHP apps)
RUN a2enmod rewrite

# Copy all project files to the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html/

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Expose port 80
EXPOSE 80
