<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/conexao/config.php';

if (!function_exists('limita_caracteres')) {
    /**
     * Trunca texto para exibição em relatórios / orçamento (legado).
     */
    function limita_caracteres($texto, int $limite = 65): string
    {
        $texto = trim((string) $texto);
        if ($texto === '') {
            return '';
        }
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            if (mb_strlen($texto, 'UTF-8') <= $limite) {
                return $texto;
            }

            return mb_substr($texto, 0, $limite, 'UTF-8') . '...';
        }
        if (strlen($texto) <= $limite) {
            return $texto;
        }

        return substr($texto, 0, $limite) . '...';
    }
}

$conexao = @mysql_connect($local, $usuario, $senha);
if (!$conexao) {
    die(
        'Erro ao conectar no MySQL: ' . mysql_error()
        . '. No InfinityFree use conexao/credentials.php com DB_HOST sqlXXX.infinityfree.com (não use localhost).'
    );
}

$db = mysql_select_db($selecione);
if (!$db) {
    die('Erro ao selecionar o banco ' . htmlspecialchars($selecione) . ': ' . mysql_error());
}

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
