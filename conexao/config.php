<?php

if (!function_exists('app_env')) {
function app_env(string $key): ?string
{
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    if (array_key_exists($key, $_SERVER)) {
        return $_SERVER[$key];
    }

    return null;
}
}

if (!function_exists('app_database_config')) {
function app_database_config(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'catalogo',
        'port' => 3306,
    ];

    $credentials = __DIR__ . '/credentials.php';
    if (is_readable($credentials)) {
        $cfg = require $credentials;
        if (is_array($cfg)) {
            $config['host'] = $cfg['DB_HOST'] ?? $config['host'];
            $config['user'] = $cfg['DB_USER'] ?? $config['user'];
            $config['pass'] = $cfg['DB_PASSWORD'] ?? $config['pass'];
            $config['name'] = $cfg['DB_NAME'] ?? $config['name'];
        }
    }

    if (($h = app_env('DB_HOST')) !== null && $h !== '') {
        $config['host'] = $h;
    }
    if (($u = app_env('DB_USER')) !== null && $u !== '') {
        $config['user'] = $u;
    }
    if (app_env('DB_PASSWORD') !== null || getenv('DB_PASSWORD') !== false || array_key_exists('DB_PASSWORD', $_ENV)) {
        $config['pass'] = app_env('DB_PASSWORD') ?? '';
    }
    if (($n = app_env('DB_NAME')) !== null && $n !== '') {
        $config['name'] = $n;
    }

    $cache = $config;

    return $cache;
}
}

$dbConfig = app_database_config();
$local = $dbConfig['host'];
$usuario = $dbConfig['user'];
$senha = $dbConfig['pass'];
$selecione = $dbConfig['name'];
