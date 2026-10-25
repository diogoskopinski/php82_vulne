<?php

require_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    if ($id > 0) {
        $_SESSION['produto']['id_' . $id]['preco'] = $_GET['preco'] ?? 0;
        $_SESSION['produto']['id_' . $id]['quantidade'] = $_GET['quantidade'] ?? 1;
        $_SESSION['produto']['id_' . $id]['id'] = $id;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Produto no carrinho</title>
</head>
<body>
    <p align="center">Produto inserido com sucesso.</p>
    <p align="center">
        <button type="button" onclick="history.go(-2)">Inserir mais produtos</button>
        <button type="button" onclick="top.location.href='carrinho.php'; if (window.parent && window.parent.jQuery && window.parent.jQuery.colorbox) { window.parent.jQuery.colorbox.close(); }">Finalizar orçamento</button>
    </p>
</body>
</html>
