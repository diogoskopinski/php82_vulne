#!/bin/bash
set -e

mkdir -p /var/www/html/fotos /var/www/html/images
php /var/www/html/docker/seed-lab-assets.php || true
php /var/www/html/docker/sync-missing-fotos.php || true
chmod -R 0777 /var/www/html/fotos /var/www/html/images 2>/dev/null || true

SHIM="/usr/local/lib/php/mysql-shim.php"
if [ -f "$SHIM" ]; then
    printf '%s\n' "auto_prepend_file = $SHIM" > /usr/local/etc/php/conf.d/98-mysql-shim.ini
fi

exec "$@"
