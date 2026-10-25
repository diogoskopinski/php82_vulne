<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$credentials = __DIR__ . '/conexao/credentials.php';
if (is_readable($credentials)) {
    $cfg = require $credentials;
    if (is_array($cfg)) {
        foreach (['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME'] as $key) {
            if (!isset($cfg[$key]) || $cfg[$key] === '') {
                continue;
            }
            $value = (string) $cfg[$key];
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

require_once __DIR__ . '/includes/mysql-shim.php';
