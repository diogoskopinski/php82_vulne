<?php

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/processa.php';
require_once __DIR__ . '/functions.php';

$conecta = new shopping();
$conecta->conexao();

$clientesLista = [];
try {
    $conexaoPdo = new Conexao();
    $db = $conexaoPdo->getConnection();
    if ($db) {
        $clienteModel = new Cliente($db);
        $clientesLista = $clienteModel->getAllclientes();
        if (!is_array($clientesLista)) {
            $clientesLista = [];
        }
    }
} catch (Throwable $e) {
    $clientesErro = $e->getMessage();
}

require __DIR__ . '/topo.php';
?>

<div class="col-lg-6">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Carrinho</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>QTD</th>
                            <th>Valor</th>
                            <th>Mais</th>
                            <th>Menos</th>
                            <th>Excluir</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $conecta->carrinho(); ?>
                    </tbody>
                </table>
            </div>

            <form name="orcamento" method="post" action="orcamento_finalizar.php" class="form-horizontal">
                <div class="form-group">
                    <label for="cliente" class="control-label">Selecione o cliente</label>
                    <div class="col-lg-12" style="padding:0">
                        <?php if (!empty($clientesErro)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($clientesErro, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php elseif (count($clientesLista) === 0): ?>
                            <div class="alert alert-warning">
                                Nenhum cliente cadastrado.
                                <a href="cliente/adicionar.php" class="alert-link">Cadastrar cliente</a>
                            </div>
                        <?php else: ?>
                            <select id="cliente" name="cliente" class="form-control" required>
                                <option value="0">Selecione</option>
                                <?php foreach ($clientesLista as $cli): ?>
                                    <option value="<?php echo (int) $cli->idCliente; ?>">
                                        <?php echo htmlspecialchars($cli->nome, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (count($clientesLista) > 0): ?>
                    <button type="submit" name="finalizarOrcamento" value="1" class="btn btn-success">
                        Finalizar orçamento
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

</div><!-- /#page-wrapper -->

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>
</body>
</html>
