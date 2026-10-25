#!/bin/bash
set -e

SHIM="/usr/local/lib/php/mysql-shim.php"
if [ -f "$SHIM" ]; then
    printf '%s\n' "auto_prepend_file = $SHIM" > /usr/local/etc/php/conf.d/98-mysql-shim.ini
fi

exec "$@"
