<?php

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/Classes/Conexao.class.php';
require_once __DIR__ . '/includes/media.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mensagem = '';
$tipoMensagem = '';

if ($id <= 0) {
    die('Produto inválido.');
}

$conexao = new Conexao();
$pdo = $conexao->getConnection();
if (!$pdo) {
    die('Sem conexão com o banco de dados.');
}

$stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$linha = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$linha) {
    die('Produto não encontrado.');
}

$foto = $linha['foto'] ?? '';

if (isset($_POST['enviar']) && $_POST['enviar'] === 'send') {
    $_UP['pasta'] = __DIR__ . '/fotos/';
    $_UP['tamanho'] = 1024 * 1024 * 2;
    $_UP['extensoes'] = ['jpg', 'jpeg', 'png', 'gif'];

    if (!is_dir($_UP['pasta']) && !mkdir($_UP['pasta'], 0777, true) && !is_dir($_UP['pasta'])) {
        $mensagem = 'Não foi possível criar a pasta fotos/.';
        $tipoMensagem = 'danger';
    } else {
        $uploadError = (int) ($_FILES['arquivo']['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($uploadError !== UPLOAD_ERR_OK) {
            $mensagem = 'Erro no upload (código ' . $uploadError . ').';
            $tipoMensagem = 'danger';
        } else {
            $partesNome = explode('.', $_FILES['arquivo']['name'] ?? '');
            $extensao = strtolower((string) end($partesNome));

            if (!in_array($extensao, $_UP['extensoes'], true)) {
                $mensagem = 'Formato inválido. Use jpg, png ou gif.';
                $tipoMensagem = 'warning';
            } elseif ($_FILES['arquivo']['size'] > $_UP['tamanho']) {
                $mensagem = 'Arquivo maior que 2 MB.';
                $tipoMensagem = 'warning';
            } else {
                $nome_final = md5((string) time()) . '.jpeg';
                $tam_name = @getimagesize($_FILES['arquivo']['tmp_name']);

                if ($tam_name === false) {
                    $mensagem = 'Arquivo de imagem inválido.';
                    $tipoMensagem = 'warning';
                } elseif ($tam_name[0] >= 801 || $tam_name[1] >= 601) {
                    $mensagem = 'Imagem deve ter no máximo 800×600 px.';
                    $tipoMensagem = 'warning';
                } elseif (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                    $upd = $pdo->prepare('UPDATE produtos SET foto = ? WHERE id = ? LIMIT 1');
                    if ($upd->execute([$nome_final, $id])) {
                        $foto = $nome_final;
                        $mensagem = 'Foto alterada com sucesso!';
                        $tipoMensagem = 'success';
                    } else {
                        $mensagem = 'Erro ao salvar no banco.';
                        $tipoMensagem = 'danger';
                    }
                } else {
                    $mensagem = 'Falha ao gravar em fotos/. Verifique permissão da pasta (chmod 777).';
                    $tipoMensagem = 'danger';
                }
            }
        }
    }
}

$fotoUrl = foto_produto_url($foto);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Alterar foto</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
        body { padding: 15px; background: #f5f5f5; }
        .preview { max-width: 100%; height: auto; border: 1px solid #ccc; background: #fff; }
    </style>
</head>
<body>
    <div class="panel panel-info">
        <div class="panel-heading"><strong>Alterar foto</strong> — <?php echo htmlspecialchars($linha['descricao'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="panel-body">
            <?php if ($mensagem !== ''): ?>
                <div class="alert alert-<?php echo htmlspecialchars($tipoMensagem, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <p><strong>Foto atual</strong></p>
            <img class="preview" src="<?php echo htmlspecialchars($fotoUrl, ENT_QUOTES, 'UTF-8'); ?>" width="280" height="210" alt="Foto atual"/>

            <form method="post" enctype="multipart/form-data" style="margin-top:15px">
                <div class="form-group">
                    <label>Nova foto (máx. 800×600, 2 MB)</label>
                    <input type="file" name="arquivo" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <input type="hidden" name="enviar" value="send">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>
