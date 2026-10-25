#!/usr/bin/env python3
"""Envia arquivos do projeto para htdocs no InfinityFree (FTP)."""
from __future__ import annotations

import ftplib
import os
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
SKIP_DIRS = {
    ".git",
    "vendor",
    "fotos",
    "scripts",
}
SKIP_FILES = {".env"}
SKIP_PREFIXES = ("docker/mysql/", "docker/apache/")


def should_skip(rel: str) -> bool:
    parts = Path(rel).parts
    if parts and parts[0] in SKIP_DIRS:
        return True
    if Path(rel).name in SKIP_FILES:
        return True
    norm = rel.replace("\\", "/")
    for prefix in SKIP_PREFIXES:
        if norm.startswith(prefix):
            return True
    if norm in ("docker-compose.yml", "Dockerfile", "sonar-project.properties"):
        return True
    if norm.endswith(".md") and norm != "README.md":
        return True
    return False


def ensure_remote_dir(ftp: ftplib.FTP, path: str) -> None:
    if not path or path == ".":
        return
    parts = path.split("/")
    built = ""
    for part in parts:
        built = f"{built}/{part}" if built else part
        try:
            ftp.cwd(built)
        except ftplib.error_perm:
            ftp.mkd(built)
            ftp.cwd(built)


def upload_tree(ftp: ftplib.FTP, local: Path, remote_base: str) -> None:
    for item in sorted(local.rglob("*")):
        rel = item.relative_to(ROOT).as_posix()
        if should_skip(rel):
            continue
        if item.is_dir():
            continue
        remote_dir = remote_base
        parent = item.parent.relative_to(ROOT).as_posix()
        if parent and parent != ".":
            remote_dir = f"{remote_base}/{parent}"
        ensure_remote_dir(ftp, remote_dir)
        ftp.cwd("/")
        with item.open("rb") as fh:
            print(f"PUT {rel}")
            ftp.storbinary(f"STOR {remote_base}/{rel}", fh)


def main() -> int:
    host = os.environ.get("FTP_HOST", "ftpupload.net")
    user = os.environ.get("FTP_USER", "if0_43271238")
    password = os.environ.get("FTP_PASSWORD")
    remote = os.environ.get("REMOTE_DIR", "htdocs")
    if not password:
        print("Defina FTP_PASSWORD", file=sys.stderr)
        return 1

    ftp = ftplib.FTP(host, timeout=120)
    ftp.login(user, password)
    ftp.set_pasv(True)
    upload_tree(ftp, ROOT, remote)
    ftp.quit()
    print("Deploy concluído.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
