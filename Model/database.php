<?php
class Database {
    private $host = "localhost";
    private $usuario = "root";
    private $contraseña = "";
    private $bd = "edugloss";
    private $conn;

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->conn = new mysqli($this->host, $this->usuario, $this->contraseña, $this->bd);
            $this->conn->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>


