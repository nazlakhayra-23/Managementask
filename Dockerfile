# PHP-FPM untuk Nginx
FROM php:8.2-fpm
# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql
# Set working directory
WORKDIR /var/www/personal_system
# Set proper permissions
RUN chown -R www-data:www-data /var/www/personal_system
# Expose port 9000 untuk PHP-FPM
EXPOSE 9000