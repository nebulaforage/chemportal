FROM php:8.2-apache

# Disable other MPMs and enable prefork (REQUIRED for PHP)
RUN a2dismod mpm_event mpm_worker \
 && a2enmod mpm_prefork

# Enable mysqli
RUN docker-php-ext-install mysqli

# Enable Apache rewrite
RUN a2enmod rewrite

# Configure Apache to listen on Railway PORT
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE ${PORT}

CMD ["apache2-foreground"]
