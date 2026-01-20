FROM debian:bullseye

# Install Apache + PHP
RUN apt-get update && apt-get install -y \
    apache2 \
    libapache2-mod-php \
    php \
    php-mysqli \
    && apt-get clean

# Disable all MPMs first
RUN a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2dismod mpm_prefork || true

# Enable ONLY prefork
RUN a2enmod mpm_prefork php8.2 rewrite

# Configure Apache to listen on Railway PORT
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/' /etc/apache2/sites-available/000-default.conf

# Copy application
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE ${PORT}

CMD ["apachectl", "-D", "FOREGROUND"]
