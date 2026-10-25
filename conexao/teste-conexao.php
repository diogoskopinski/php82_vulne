<?php

/**
 * Diagnóstico rápido no InfinityFree — apague depois do lab.
 * Acesse: /conexao/teste-conexao.php
 */
header('Content-Type: text/plain; charset=utf-8');

require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/conexao/config.php';

$cfg = app_database_config();
echo "Host: {$cfg['host']}\n";
echo "Banco: {$cfg['name']}\n";
echo "Usuario: {$cfg['user']}\n";

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $cfg['host'],
        (int) ($cfg['port'] ?? 3306),
        $cfg['name']
    );
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $n = (int) $pdo->query('SELECT COUNT(*) FROM produtos')->fetchColumn();
    echo "OK — produtos na tabela: {$n}\n";
} catch (Throwable $e) {
    echo 'ERRO: ' . $e->getMessage() . "\n";
}
