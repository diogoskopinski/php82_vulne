<?php

$credentials = __DIR__ . '/credentials.php';
if (is_readable($credentials)) {
    $cfg = require $credentials;
    if (is_array($cfg)) {
        foreach (['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME'] as $key) {
            if (!empty($cfg[$key])) {
                putenv($key . '=' . $cfg[$key]);
            }
        }
    }
}

$local = getenv('DB_HOST') ?: 'localhost';
$usuario = getenv('DB_USER') ?: 'root';
$senha = getenv('DB_PASSWORD') ?: '';
$selecione = getenv('DB_NAME') ?: 'catalogo';
?>
