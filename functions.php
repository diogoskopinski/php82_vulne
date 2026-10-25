<?php

include __DIR__ . '/conexao/config.php';

$conexao = mysql_connect($local, $usuario, $senha)
    or die(mysql_error());
$db = mysql_select_db($selecione)
    or die(mysql_error());

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
