# PHP 8.2.27 — linha 8.2 anterior a abril/2026 (lab de vulnerabilidades)
FROM php:8.2.27-apache-bookworm

RUN a2enmod rewrite headers

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        libzip-dev \
    && docker-php-ext-install mysqli pdo_mysql \
    && git clone --depth 1 https://github.com/dshafik/php-mysql.git /tmp/php-mysql \
    && cp /tmp/php-mysql/src/mysql.php /usr/local/lib/php/mysql-shim.php \
    && rm -rf /tmp/php-mysql \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/lab.ini /usr/local/etc/php/conf.d/99-lab.ini
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
