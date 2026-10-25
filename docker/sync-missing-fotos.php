<?php

/**
 * Gera JPEG mínimo para cada nome de foto no banco sem arquivo em fotos/.
 */

$root = dirname(__DIR__);
$fotosDir = $root . '/fotos';

if (!is_dir($fotosDir)) {
    mkdir($fotosDir, 0755, true);
}

$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'sistema';
$pass = getenv('DB_PASSWORD') ?: 'sistema_pass';
$db = getenv('DB_NAME') ?: 'catalogo';

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    fwrite(STDERR, "sync-missing-fotos: DB indisponível ({$mysqli->connect_error})\n");
    exit(0);
}

$jpeg = base64_decode(
    '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDABALDA4MChAODQ4SERATGCgaGBYWGDEjJR0oOjM9PDkzODdASFxOQERXRTc4UG1RV19iZ2hnPk1xeXBkeFxlZ2P/2wBDAQcNDA0CASxDSEIBCf/xAAbAAADAAMBAQAAAAAAAAAAAAAEBQYHCAECA//EAD0QAAIBAwMCBAMFBQQDAAIBBQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoKS0fH/xAAZAQADAQEBAAAAAAAAAAAAAAABAgMABAX/xAAmEQACAgEDAwUBAAAAAAAAAAABAgMRACEEEjFBUWFx8IGRobHB0f/aAAwDAQACEQMRAD8A6pSlKUr0pSlKAH//2Q=='
);

if ($jpeg === false || $jpeg === '') {
    exit(0);
}

$limit = (int) (getenv('SYNC_FOTOS_LIMIT') ?: 1500);
$result = $mysqli->query(
    "SELECT DISTINCT foto FROM produtos WHERE foto IS NOT NULL AND TRIM(foto) <> '' LIMIT {$limit}"
);

if (!$result) {
    exit(0);
}

while ($row = $result->fetch_assoc()) {
    $basename = basename($row['foto']);
    $target = $fotosDir . '/' . $basename;
    if (!is_file($target) || filesize($target) === 0) {
        file_put_contents($target, $jpeg);
    }
}

$result->free();
$mysqli->close();
