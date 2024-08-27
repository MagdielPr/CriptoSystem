<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '1234');
define('DB_NAME', 'criptomoedas');

function getConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Conexão falhou: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        error_log("Erro de conexão: " . $e->getMessage());
        return false;
    }
}
?>