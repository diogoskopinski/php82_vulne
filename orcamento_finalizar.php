<?php

require_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

$idOrcamento = null;
$salvo = false;

$clienteId = isset($_POST['cliente']) ? (int) $_POST['cliente'] : (isset($_GET['cliente']) ? (int) $_GET['cliente'] : 0);

if ($clienteId > 0 && !empty($_SESSION['produto']) && is_array($_SESSION['produto'])) {
    $sql = mysql_query('SELECT idOrcamento FROM orcamento ORDER BY idOrcamento DESC LIMIT 1');
    if ($res = mysql_fetch_array($sql)) {
        $idOrcamento = (int) $res[0] + 1;
    } else {
        $idOrcamento = 1;
    }

    foreach ($_SESSION['produto'] as $campo) {
        if (empty($campo['id']) || (int) $campo['id'] <= 0) {
            continue;
        }
        $id = (int) $campo['id'];
        $preco = mysql_real_escape_string((string) $campo['preco']);
        $quantidade = (int) $campo['quantidade'];
        $clienteEsc = mysql_real_escape_string((string) $clienteId);
        $orcEsc = mysql_real_escape_string((string) $idOrcamento);

        mysql_query(
            "INSERT INTO orcamento (idProduto, idOrcamento, idCliente, preco, quantidade, dataHora) "
            . "VALUES ('$id', '$orcEsc', '$clienteEsc', '$preco', '$quantidade', NOW())"
        );
        $_SESSION['produto']['id_' . $id]['id'] = 0;
        $_SESSION['produto']['id_' . $id]['quantidade'] = 0;
        $_SESSION['produto']['id_' . $id]['preco'] = 0;
    }
    $salvo = true;
}

require __DIR__ . '/topo.php';
?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Orçamento</li>
        </ol>
    </div>
</div>

<div class="col-lg-12">
    <?php if ($salvo && $idOrcamento !== null): ?>
        <div class="alert alert-dismissable alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Feito!</strong> Orçamento salvo com sucesso (nº <?php echo (int) $idOrcamento; ?>).
        </div>
        <script>
        (function () {
            var width = 595, height = 421, left = 0, top = 0;
            var url = 'orcamento_visualizar.php?idOrcamento=<?php echo (int) $idOrcamento; ?>';
            window.open(url, 'janela', 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
        })();
        </script>
    <?php else: ?>
        <div class="alert alert-warning">
            Não foi possível salvar o orçamento. Selecione um cliente e adicione produtos ao carrinho.
            <a href="carrinho.php" class="alert-link">Voltar ao carrinho</a>
        </div>
    <?php endif; ?>
</div>

</div><!-- /#page-wrapper -->

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
