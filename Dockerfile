FROM php:8.1-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev postgresql-client unzip git \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql
RUN a2enmod rewrite

# Configuration permissions
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
CMD ["apache2-foreground"]