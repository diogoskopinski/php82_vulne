#!/usr/bin/env bash
# Envia o projeto para htdocs no InfinityFree via FTP.
# Uso: FTP_PASSWORD='sua_senha' ./scripts/deploy-infinityfree.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

FTP_HOST="${FTP_HOST:-ftpupload.net}"
FTP_USER="${FTP_USER:-if0_43271238}"
FTP_PASSWORD="${FTP_PASSWORD:?Defina FTP_PASSWORD (senha FTP do painel)}"
REMOTE_DIR="${REMOTE_DIR:-htdocs}"

if ! command -v lftp >/dev/null 2>&1; then
  echo "Instale lftp: sudo apt install lftp" >&2
  exit 1
fi

lftp -u "$FTP_USER","$FTP_PASSWORD" "$FTP_HOST" <<EOF
set ftp:ssl-allow no
set cmd:fail-exit yes
lcd $ROOT
cd $REMOTE_DIR
mirror --reverse --delete --verbose \
  --exclude-glob .git/ \
  --exclude-glob .git/** \
  --exclude-glob .env \
  --exclude-glob vendor/ \
  --exclude-glob fotos/* \
  --exclude-glob docker/mysql/ \
  --exclude-glob docker/apache/ \
  --exclude-glob conexao/credentials.php \
  --exclude-glob '*.md' \
  --exclude-glob scripts/ \
  --exclude-glob docker-compose.yml \
  --exclude-glob Dockerfile \
  --exclude-glob sonar-project.properties \
  .
bye
EOF

echo "Deploy FTP concluído em /$REMOTE_DIR"
