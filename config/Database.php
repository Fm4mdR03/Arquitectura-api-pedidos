<?php
// config/Database.php
class Database {
    private $host = "localhost";
    private $db_name = "sistema_pedidos";
    private $username = "root"; // Usuario por defecto en Laragon
    private $password = "";     // Contraseña vacía por defecto en Laragon
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>