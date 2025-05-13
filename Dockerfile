# Base PHP image with Apache
FROM php:8.1-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project files
COPY . .

# Set permissions for Laravel
RUN chown -R www-data:www-data \
    storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# 🔧 Fix: Change Apache DocumentRoot to Laravel's /public directory
# Update DocumentRoot in default site
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Append a correct <Directory> block for Laravel's /public directory
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Expose port 80 for Apache
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
