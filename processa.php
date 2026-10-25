<?php

require_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pagina = 'carrinho.php';

class shopping
{
    private $banco;
    private $senha;
    private $login;
    private $hostname;

    public function __construct()
    {
        require_once __DIR__ . '/conexao/config.php';
        global $selecione, $senha, $usuario, $local;
        $this->banco = $selecione ?? 'catalogo';
        $this->senha = $senha ?? '';
        $this->login = $usuario ?? 'root';
        $this->hostname = $local ?? 'localhost';
    }

    public function conexao()
    {
        if (!@mysql_connect($this->hostname, $this->login, $this->senha)) {
            die('ERRO ' . mysql_error());
        }
        if (!mysql_select_db($this->banco)) {
            die('ERRO ' . mysql_error());
        }
        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
    }

    public function carrinho()
    {
        if (empty($_SESSION['produto']) || !is_array($_SESSION['produto'])) {
            echo '<tr><td colspan="7" align="center" valign="middle">CARRINHO VAZIO</td></tr>';
            return;
        }

        $Total = 0;
        foreach ($_SESSION['produto'] as $campo) {
            if (empty($campo['id']) || (int) $campo['id'] <= 0) {
                continue;
            }
            $id = (int) $campo['id'];
            $PD = mysql_query(
                'SELECT id, codigo, produto, descricao, estoque, preco FROM produtos WHERE id='
                . mysql_real_escape_string((string) $id)
            );
            while ($list = mysql_fetch_assoc($PD)) {
                $subTotal = $campo['quantidade'] * $campo['preco'];
                echo '<tr>
                <td width="53%" height="44" bgcolor="FFFFFF">' . htmlspecialchars($list['produto'] . ' ' . $list['descricao'], ENT_QUOTES, 'UTF-8') . '</td>
                <td width="7%" height="44" align="center" valign="middle" bgcolor="#FFFFFF">' . (int) $campo['quantidade'] . ' X </td>
                <td width="11%" height="44" align="center" valign="middle" bgcolor="#FFFFFF">R$ ' . number_format((float) $campo['preco'], 2, ',', '.') . '</td>
                <td width="6%" height="44" align="center" valign="middle" bgcolor="#FFFFFF">
                <a href="processa.php?add=' . $id . '">
                <img src="images/mais.png" width="44" height="44" border="0" alt="+"/></a>
                </td>
                <td width="6%" height="44" align="center" valign="middle" bgcolor="#FFFFFF">
                <a href="processa.php?menos=' . $id . '">
                <img src="images/menos.png" width="44" height="44" border="0" alt="-"/></a>
                </td>
                <td width="6%" height="44" align="center" valign="middle" bgcolor="#FFFFFF">
                <a href="processa.php?del=' . $id . '">
                <img src="images/excluir.png" width="44" height="44" border="0" alt="x"/></a>
                </td>
                <td width="11%" height="44" align="center" valign="middle" bgcolor="#FFFFFF"> R$ ' . number_format((float) $subTotal, 2, ',', '.') . '</td></tr>';
                $Total += $subTotal;
            }
        }

        if ($Total == 0) {
            echo '<tr><td colspan="7" align="center" valign="middle">CARRINHO VAZIO</td></tr>';
            return;
        }

        echo '
        <tr>
        <td colspan="4"></td>
        <td colspan="2" align="center" valign="middle">Total</td>
        <td align="center" valign="middle" bgcolor="#FFFFFF" height="44">R$ ' . number_format((float) $Total, 2, ',', '.') . '</td>
        </tr>
        <tr>
        <td colspan="6"></td>
        <td align="center" valign="middle" bgcolor="#FFFFFF" height="44">
        <a href="index.php"><img src="images/home.png" align="left" border="0" alt="Home"></a>
        </td>
        </tr>';
    }

    /** @deprecated Use lista em carrinho.php via PDO (Cliente::getAllclientes) */
    public function clientes()
    {
        require_once __DIR__ . '/Classes/Conexao.class.php';
        require_once __DIR__ . '/Classes/Cliente.class.php';
        $db = (new Conexao())->getConnection();
        if (!$db) {
            echo '<div class="alert alert-danger">Sem conexão com o banco.</div>';
            return;
        }
        $lista = (new Cliente($db))->getAllclientes();
        if (!is_array($lista) || count($lista) === 0) {
            echo '<div class="alert alert-warning">Nenhum cliente cadastrado. <a href="cliente/adicionar.php">Cadastrar</a></div>';
            return;
        }
        echo '<select id="cliente" name="cliente" class="form-control" required>';
        echo '<option value="0">Selecione</option>';
        foreach ($lista as $cli) {
            echo '<option value="' . (int) $cli->idCliente . '">'
                . htmlspecialchars($cli->nome, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        echo '</select>';
        echo '<button type="submit" name="finalizarOrcamento" value="1" class="btn btn-success">Finalizar orçamento</button>';
    }
}

if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    if ($id > 0) {
        if (!isset($_SESSION['produto']['id_' . $id])) {
            $_SESSION['produto']['id_' . $id] = ['id' => $id, 'quantidade' => 0, 'preco' => 0];
        }
        $_SESSION['produto']['id_' . $id]['quantidade'] += 1;
    }
    header('Location: ' . $pagina);
    exit;
}

if (isset($_GET['menos'])) {
    $id = (int) $_GET['menos'];
    if ($id > 0 && isset($_SESSION['produto']['id_' . $id])) {
        $_SESSION['produto']['id_' . $id]['quantidade']--;
        if ($_SESSION['produto']['id_' . $id]['quantidade'] < 1) {
            $_SESSION['produto']['id_' . $id]['quantidade'] = 1;
        }
    }
    header('Location: ' . $pagina);
    exit;
}

if (isset($_GET['del'])) {
    $id = (int) $_GET['del'];
    if ($id > 0 && isset($_SESSION['produto']['id_' . $id])) {
        $_SESSION['produto']['id_' . $id]['id'] = 0;
        $_SESSION['produto']['id_' . $id]['quantidade'] = 0;
        $_SESSION['produto']['id_' . $id]['preco'] = 0;
    }
    header('Location: ' . $pagina);
    exit;
}
