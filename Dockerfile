# PHP 8.2.27 — linha 8.2 anterior a abril/2026 (lab de vulnerabilidades)
FROM php:8.2.27-apache-bookworm

RUN a2enmod rewrite headers

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
    && docker-php-ext-install mysqli pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/mysql-shim.php /usr/local/lib/php/mysql-shim.php
COPY docker/php/lab.ini /usr/local/etc/php/conf.d/99-lab.ini
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
