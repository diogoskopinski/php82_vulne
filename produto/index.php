<?php

$root = dirname(__DIR__);
$pageError = null;
$dados = [];
$total = 0;
$paginas = 1;
$pagina = 1;
$inicio = 1;
$fim = 1;

try {
    require_once $root . '/includes/init.php';
    require_once $root . '/Classes/Conexao.class.php';
    require_once $root . '/Classes/Produto.class.php';
    require_once $root . '/includes/media.php';

    $conexao = new Conexao();
    $db = $conexao->getConnection();
    $produto = new Produto($db);

    if (!$db) {
        throw new RuntimeException(
            'Sem conexão PDO. Verifique conexao/credentials.php (host sql301.infinityfree.com, banco if0_43271238_vendas).'
        );
    }

    $_BS['PorPagina'] = 50;
    $limite = 3;

    $total = $produto->getTotalProdutos();
    $paginas = max(1, (int) ceil($total / $_BS['PorPagina']));

    $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    $pagina = max(min($paginas, $pagina), 1);

    $offset = ($pagina - 1) * $_BS['PorPagina'];
    $inicio = ((($pagina - $limite) > 1) ? $pagina - $limite : 1);
    $fim = ((($pagina + $limite) < $paginas) ? $pagina + $limite : $paginas);

    $dados = $produto->getAllProdutos($offset, $_BS['PorPagina']);
} catch (Throwable $e) {
    $pageError = $e->getMessage() . ' [' . basename($e->getFile()) . ':' . $e->getLine() . ']';
}

require $root . '/topo.php';

if ($pageError !== null) {
    echo '<div class="alert alert-danger"><strong>Erro:</strong> '
        . htmlspecialchars($pageError, ENT_QUOTES, 'UTF-8') . '</div>';
}
?>


<div class="row">
    <div class="col-lg-12">
        <h1><small>Lista de produtos</small></h1>
    </div>
</div><!-- /.row -->

<div class="col-lg-12">
</div>
<div class="panel-body">
    <div class="table-responsive">
        <table id="table" class="table table-bordered table-hover table-responsive table-striped table-condensed" >
            <thead>
                <tr bgcolor="#CCC">
                    <th>Foto</th>
                    <th>Codigo</th>
                    <th>Produto</th>
                    <th>Descricao</th>
                    <th>QTD</th>
                    <th>Original</th>
                    <th>Paralelo</th>
                    <th>Valor</th>
                    <th>Promoção</th>
                    <th>Opções</th>
                </tr>

            </thead>
            <tbody class="searchable">

            <div class="well well-sm">
                <?php
                echo '<p>Total de ' . (int) $total . ' produtos encontrados.</p>';
                ?>
                <input type="text" id="search" placeholder="Digite para pesquisar">
            </div>
            <tr>
                <?php foreach ($dados as $reg): ?>
                    <?php $fotoUrl = foto_produto_url($reg->foto ?? ''); ?>
                    <td><img width="48" height="36" alt="" src="<?php echo htmlspecialchars($fotoUrl, ENT_QUOTES, 'UTF-8'); ?>"/></td>
                    <td><a href="editar.php?id=<?php echo (int) $reg->id; ?>"><?php echo htmlspecialchars($reg->codigo ?? '', ENT_QUOTES, 'UTF-8'); ?></a></td>
                    <td><?php echo htmlspecialchars($reg->produto ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($reg->descricao ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($reg->estoque ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($reg->codigo_original ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($reg->codigo_paralelo ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($reg->preco ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($reg->promocao ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                    <td width="120px">
                        <a class="btn btn-xs btn-danger" href="apagar.php?id=<?php echo (int) $reg->id; ?>" onclick="return confirm('Confirmar exclusão?');">Excluir</a>
                        <a class="iframe btn btn-xs btn-info" href="../alterar_foto.php?id=<?php echo (int) $reg->id; ?>">Foto</a>
                        <a class="iframe btn btn-xs btn-success" href="../carrinho_inserir_dados.php?add=<?php echo (int) $reg->id; ?>">+</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="text-right">
        <ul class="pagination">
            <li>
                <?php
                echo '<li><a href="index.php"> Inicio </a></li>';
                if ($paginas > 1 && $pagina <= $paginas) {
                    for ($i = $inicio; $i <= $fim; $i++) {
                        if ($i == $pagina) {
                            echo '<li class="active"><a href="index.php?pagina=' . $i . '">' . $i . '</a></li>';
                        } else {
                            echo '<li><a href="index.php?pagina=' . $i . '">' . $i . '</a></li>';
                        }
                    }
                }
                echo '<li><a href="index.php?pagina=' . $paginas . '"> Fim </a></li>';
                ?>
            </li>
        </ul>

    </div>
</div>
</div><!-- /.row -->
</div><!-- /#page-wrapper -->

<script src="../js/jquery.colorbox.js"></script>
<script>
$(".iframe").colorbox({iframe: true, width: "90%", height: "90%", maxWidth: "520px", maxHeight: "90%"});
$("#search").keyup(function () {
    var _this = this;
    $.each($("#table tbody tr"), function () {
        if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
});
</script>
</body>
</html>
