#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DEST="$ROOT/vendor/dshafik/php-mysql/src/mysql.php"
mkdir -p "$(dirname "$DEST")"

URLS=(
  "https://raw.githubusercontent.com/dshafik/php-mysql/0.2.2/src/mysql.php"
  "https://raw.githubusercontent.com/dshafik/php-mysql/v0.2.2/src/mysql.php"
  "https://cdn.jsdelivr.net/gh/dshafik/php-mysql@0.2.2/src/mysql.php"
)

for url in "${URLS[@]}"; do
  if curl -fsSL "$url" -o "$DEST" && head -1 "$DEST" | grep -q '<?php'; then
    echo "OK: $url"
    exit 0
  fi
done

echo "Não foi possível baixar o shim mysql. Copie manualmente para $DEST" >&2
exit 1
