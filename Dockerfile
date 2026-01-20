FROM php:8.2-apache

# ---- FORCE Apache to use ONLY prefork MPM ----
RUN a2dismod mpm_event || true \
 && a2dismod mpm_worker || true \
 && a2dismod mpm_prefork || true \
 && a2enmod mpm_prefork

# ---- PHP extensions ----
RUN docker-php-ext-install mysqli

# ---- Apache modules ----
RUN a2enmod rewrite

# ---- Configure Apache for Railway PORT ----
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -i 's/:80/:${PORT}/' /etc/apache2/sites-available/000-default.conf

# ---- Copy app files ----
COPY . /var/www/html/

# ---- Permissions ----
RUN chown -R www-data:www-data /var/www/html

EXPOSE ${PORT}

CMD ["apache2-foreground"]
