<?php

$root = dirname(__DIR__);
$pageError = null;
$dados = [];

try {
    require_once $root . '/includes/init.php';
    require_once $root . '/Classes/Conexao.class.php';
    require_once $root . '/Classes/ContasPagar.class.php';

    $conexao = new Conexao();
    $db = $conexao->getConnection();
    if (!$db) {
        throw new RuntimeException('Sem conexão com o banco de dados.');
    }

    $contaspagar = new ContasPagar($db);
    $dados = $contaspagar->contasPagarHoje();
} catch (Throwable $e) {
    $pageError = $e->getMessage();
    $contaspagar = null;
}

require $root . '/topo.php';

if ($pageError !== null) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($pageError, ENT_QUOTES, 'UTF-8') . '</div>';
}
?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Gerenciar boletos</li>
        </ol>
    </div>
</div>

<?php if ($contaspagar): ?>
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i> Contas a pagar neste mês</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr><th>Mês</th><th>Total</th></tr>
                    <tr>
                        <td><?php echo htmlspecialchars($contaspagar->getNomeMes(date('m')), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo $contaspagar->contasMes(); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i> Contas a pagar próximo mês</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr><th>Mês</th><th>Total</th></tr>
                    <tr>
                        <td><?php echo htmlspecialchars($contaspagar->getNomeMes(date('m', strtotime('+1 month'))), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo $contaspagar->contasProximoMes(); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-money"></i> Boletos do dia: <?php $contaspagar->getHoje(); ?>
                    <form method="GET" name="consulta_data" action="../busca_boleto.php" style="display:inline;margin-left:10px">
                        <input id="consulta_data" name="consulta_data" type="date"/>
                        <input type="submit" value="Buscar" class="btn btn-xs btn-default"/>
                    </form>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Fornecedor</th>
                            <th>Nr Documento</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $reg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reg->fornecedor ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($reg->numero_documento ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(implode('/', array_reverse(explode('-', $reg->data ?? ''))), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($reg->valor ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if (($reg->status ?? '') === 'pago'): ?>
                                        <span class="label label-success">Pago</span>
                                    <?php else: ?>
                                        <span class="label label-danger">Aberto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-info iframe" href="../alterar_boleto.php?id=<?php echo (int) $reg->id; ?>">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="font-size:18px;color:#F00">Total do dia: <?php echo $contaspagar->totalContasHoje(); ?></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

</div><!-- /#page-wrapper -->

<script src="../js/jquery.colorbox.js"></script>
<script>
$('.iframe').colorbox({iframe: true, width: '90%', height: '90%', maxWidth: '600px'});
</script>
</body>
</html>
