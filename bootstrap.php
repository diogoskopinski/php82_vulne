<?php

$credentials = __DIR__ . '/conexao/credentials.php';
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

$shim = __DIR__ . '/vendor/dshafik/php-mysql/src/mysql.php';
if (is_file($shim)) {
    require_once $shim;
}
