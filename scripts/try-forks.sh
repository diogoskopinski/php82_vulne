#!/usr/bin/env bash
for u in JBlond/php-mysql-backwards-compatibility kylekatarnls/php-mysql-bz; do
  code=$(curl -sL -o /dev/null -w "%{http_code}" "https://raw.githubusercontent.com/$u/master/src/mysql.php")
  echo "$u $code"
done
