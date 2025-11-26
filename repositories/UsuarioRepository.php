<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerPorId($id) {
        $query = "SELECT id, nombre, email FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $usuario = new Usuario();
            $usuario->id = $row['id'];
            $usuario->nombre = $row['nombre'];
            $usuario->email = $row['email'];
            return $usuario;
        }
        return null;
    }
}
?>