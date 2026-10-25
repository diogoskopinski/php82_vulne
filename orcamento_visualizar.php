<?php

require_once __DIR__ . '/functions.php';

$nome = '';
$endereco = '';
$telefone = '';
$dataHora = '';
$idOrcamento = isset($_GET['idOrcamento']) ? (int) $_GET['idOrcamento'] : 0;

if ($idOrcamento > 0) {
    $idEsc = mysql_real_escape_string((string) $idOrcamento);
    $query = "SELECT A.nome, A.endereco, A.telefone, B.idOrcamento, "
        . "DATE_FORMAT(B.dataHora, '%d/%m/%Y %H:%i') AS dataHora "
        . "FROM clientes A INNER JOIN orcamento B ON A.idCliente = B.idCliente "
        . "WHERE B.idOrcamento = '$idEsc' LIMIT 1";
    $sql = mysql_query($query);
    if ($sql && ($res = mysql_fetch_array($sql))) {
        $nome = $res[0];
        $endereco = $res[1];
        $telefone = $res[2];
        $idOrcamento = (int) $res[3];
        $dataHora = $res[4];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <link href="css/estilo_orcamento.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        @media print { .noprint { display: none; } }
    </style>
    <title>Orçamento #<?php echo (int) $idOrcamento; ?></title>
</head>
<body>

<div class="container">
    <div class="col-xs-12">
        <div class="invoice-title">
            <img src="images/logo_nota.png" alt="Logo">
            <h3 class="pull-right">Ordem #<?php echo (int) $idOrcamento; ?></h3>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-6">
                <strong>Cliente:</strong> <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?><br>
                <strong>Endereço:</strong> <?php echo htmlspecialchars($endereco, ENT_QUOTES, 'UTF-8'); ?><br>
                <?php if ($telefone !== ''): ?>
                    <strong>Telefone:</strong> <?php echo htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8'); ?><br>
                <?php endif; ?>
            </div>
            <div class="col-xs-6 text-right">
                <strong>Data ordem:</strong> <?php echo htmlspecialchars($dataHora, ENT_QUOTES, 'UTF-8'); ?><br><br>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <table class="table table-condensed">
            <thead>
                <tr bgcolor="#CCC">
                    <td><font size="2"><strong>Código</strong></font></td>
                    <td class="text-center"><font size="2"><strong>Descrição</strong></font></td>
                    <td class="text-center"><font size="2"><strong>Qtd</strong></font></td>
                    <td class="text-center"><font size="2"><strong>Preço</strong></font></td>
                    <td class="text-right"><font size="2"><strong>Total</strong></font></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if ($idOrcamento > 0) {
                    $idEsc = mysql_real_escape_string((string) $idOrcamento);
                    $sqlItens = mysql_query(
                        'SELECT A.descricao, B.quantidade, B.preco, A.codigo, A.produto '
                        . 'FROM produtos A INNER JOIN orcamento B ON A.id = B.idProduto '
                        . "WHERE B.idOrcamento = '$idEsc' ORDER BY A.descricao ASC"
                    );
                    if ($sqlItens) {
                        while ($res = mysql_fetch_array($sqlItens)) {
                            $linhaTotal = (float) $res[1] * (float) $res[2];
                            $total += $linhaTotal;
                            $desc = limita_caracteres($res[4] . ' ' . $res[0], 65);
                            ?>
                            <tr>
                                <td valign="top"><font size="1"><?php echo htmlspecialchars($res[3], ENT_QUOTES, 'UTF-8'); ?></font></td>
                                <td valign="top"><font size="1"><?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'); ?></font></td>
                                <td valign="top" align="center"><font size="1"><?php echo htmlspecialchars((string) $res[1], ENT_QUOTES, 'UTF-8'); ?></font></td>
                                <td valign="top"><font size="1"><?php echo number_format((float) $res[2], 2, ',', '.'); ?></font></td>
                                <td valign="top"><font size="1"><?php echo number_format($linhaTotal, 2, ',', '.'); ?></font></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <tr>
                    <td class="thick-line"></td>
                    <td class="thick-line"></td>
                    <td class="thick-line"></td>
                    <td class="thick-line text-right"><strong>Total</strong></td>
                    <td class="thick-line text-right"><?php echo number_format($total, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="noprint text-center">
        <button type="button" onclick="window.print()">Imprimir</button>
    </p>
</div>

</body>
</html>
