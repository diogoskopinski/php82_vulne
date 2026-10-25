<?php

/*
 * Classe para operacoes CRUD na tabela Contas Pagar   
 */

class ContasPagar {

    // Conexão e declaração de atributos
    private $conn;
    public $id;
    public $fornecedor;
    public $numero_documento;
    public $data;
    public $valor;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    function getId() {
        return $this->id;
    }

    function getFornecedor() {
        return $this->fornecedor;
    }

    function getNumero_documento() {
        return $this->numero_documento;
    }

    function getData() {
        return $this->data;
    }

    function getValor() {
        return $this->valor;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFornecedor($fornecedor) {
        $this->fornecedor = $fornecedor;
    }

    function setNumero_documento($numero_documento) {
        $this->numero_documento = $numero_documento;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getHoje()
    {
        echo date('d/m/Y');
    }

    function getNomeMes($mes)
    {
        $mes = (int) $mes;
        $nomes = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        return $nomes[$mes] ?? (string) $mes;
    }
    
    function getMes() {
        echo date('m');
    }
    
    function getAno() {
        echo date('Y'); 
    }

    public function contasPagarHoje()
    {
        if (!$this->conn) {
            return [];
        }

        try {
            $hoje = date('Y-m-d');
            $sql = 'SELECT * FROM contas_pagar WHERE data = :hoje ORDER BY fornecedor ASC';
            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':hoje', $hoje);
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ) ?: [];
        } catch (PDOException $erro) {
            echo '<div class="alert alert-danger">Erro ao listar boletos: '
                . htmlspecialchars($erro->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';

            return [];
        }
    }

    public function totalContasHoje()
    {
        if (!$this->conn) {
            return '0,00';
        }

        try {
            $hoje = date('Y-m-d');
            $sql = 'SELECT SUM(CAST(REPLACE(valor, ",", ".") AS DECIMAL(12,2))) FROM contas_pagar WHERE data = :hoje';
            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':hoje', $hoje);
            $stm->execute();
            $total = $stm->fetchColumn();

            return number_format((float) $total, 2, ',', '.');
        } catch (PDOException $erro) {
            return '0,00';
        }
    }

    function contasMes()
    {
        if (!$this->conn) {
            return '0,00';
        }

        try {
            $inicio = date('Y-m-01');
            $fim = date('Y-m-t');
            $sql = 'SELECT SUM(CAST(REPLACE(valor, ",", ".") AS DECIMAL(12,2))) FROM contas_pagar WHERE data BETWEEN :inicio AND :fim';
            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':inicio', $inicio);
            $stm->bindValue(':fim', $fim);
            $stm->execute();

            return number_format((float) $stm->fetchColumn(), 2, ',', '.');
        } catch (PDOException $erro) {
            return '0,00';
        }
    }

    function contasProximoMes()
    {
        if (!$this->conn) {
            return '0,00';
        }

        try {
            $inicio = date('Y-m-01', strtotime('+1 month'));
            $fim = date('Y-m-t', strtotime('+1 month'));
            $sql = 'SELECT SUM(CAST(REPLACE(valor, ",", ".") AS DECIMAL(12,2))) FROM contas_pagar WHERE data BETWEEN :inicio AND :fim';
            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':inicio', $inicio);
            $stm->bindValue(':fim', $fim);
            $stm->execute();

            return number_format((float) $stm->fetchColumn(), 2, ',', '.');
        } catch (PDOException $erro) {
            return '0,00';
        }
    }

}
