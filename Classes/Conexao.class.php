<?php

class Conexao {

    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port = 3306;
    public $conn;

    public function __construct()
    {
        require_once __DIR__ . '/../conexao/config.php';
        $cfg = app_database_config();
        $this->host = $cfg['host'];
        $this->db_name = $cfg['name'];
        $this->username = $cfg['user'];
        $this->password = $cfg['pass'];
        $this->port = (int) ($cfg['port'] ?? 3306);
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $this->host,
                $this->port,
                $this->db_name
            );
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $exception) {
            echo '<div class="alert alert-danger" style="margin-top:70px">Connection error: '
                . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8')
                . ' — host: <code>' . htmlspecialchars($this->host, ENT_QUOTES, 'UTF-8')
                . '</code>, banco: <code>' . htmlspecialchars($this->db_name, ENT_QUOTES, 'UTF-8')
                . '</code>. Verifique <code>conexao/credentials.php</code>.</div>';
        }

        return $this->conn;
    }

}
