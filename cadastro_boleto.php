<?php

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/functions.php';

$mensagem = '';
$tipoMensagem = '';

if (isset($_POST['enviar']) && $_POST['enviar'] === 'send') {
    $fornecedor = strip_tags(trim($_POST['fornecedor'] ?? ''));
    $numero_documento = strip_tags(trim($_POST['numero_documento'] ?? ''));
    $data = strip_tags(trim($_POST['data'] ?? ''));
    $data = implode('-', array_reverse(explode('/', $data)));
    $valor = strip_tags(trim($_POST['valor'] ?? ''));
    $status = strip_tags(trim($_POST['status'] ?? 'aberto'));

    $fornecedorEsc = mysql_real_escape_string($fornecedor);
    $numeroEsc = mysql_real_escape_string($numero_documento);
    $dataEsc = mysql_real_escape_string($data);
    $valorEsc = mysql_real_escape_string($valor);
    $statusEsc = mysql_real_escape_string($status);

    $cadastro = mysql_query(
        "INSERT INTO contas_pagar (fornecedor, numero_documento, data, valor, status) "
        . "VALUES ('$fornecedorEsc', '$numeroEsc', '$dataEsc', '$valorEsc', '$statusEsc')"
    );

    if ($cadastro) {
        $mensagem = 'Boleto cadastrado com sucesso.';
        $tipoMensagem = 'success';
    } else {
        $mensagem = 'Erro ao cadastrar: ' . mysql_error();
        $tipoMensagem = 'danger';
    }
}

require __DIR__ . '/topo.php';
?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Cadastro Boletos</li>
        </ol>
    </div>
</div>

<div class="col-lg-4">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-money"></i> Cadastrar boleto</h3>
        </div>
        <div class="panel-body">
            <?php if ($mensagem !== ''): ?>
                <div class="alert alert-<?php echo htmlspecialchars($tipoMensagem, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fornecedor">Fornecedor</label>
                    <input type="text" required name="fornecedor" class="form-control input-sm" id="fornecedor" placeholder="Nome do Fornecedor">
                </div>
                <div class="form-group">
                    <label for="numero_documento">Número documento</label>
                    <input type="text" required name="numero_documento" class="form-control input-sm" id="numero_documento" placeholder="Número do documento">
                </div>
                <div class="form-group">
                    <label for="data">Data (dd/mm/aaaa)</label>
                    <input type="text" required name="data" class="form-control input-sm data" id="data" placeholder="Dia do vencimento">
                </div>
                <div class="form-group">
                    <label for="valor">Valor</label>
                    <input type="text" required name="valor" class="form-control input-sm" id="valor" placeholder="Valor do boleto">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" id="status">
                        <option value="aberto">Aberto</option>
                        <option value="pago">Pago</option>
                    </select>
                </div>
                <input type="hidden" name="enviar" value="send">
                <button type="submit" class="btn btn-primary" name="Enviar">Salvar</button>
            </form>
        </div>
    </div>
</div>

</div><!-- /#page-wrapper -->

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script>
$(function () {
    $('input.data').mask('99/99/9999');
});
</script>
</body>
</html>
