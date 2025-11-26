<?php
require_once __DIR__ . '/../models/Pedido.php';

class PedidoRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function guardar(Pedido $pedido) {
        $query = "INSERT INTO pedidos (producto, monto, usuario_id) VALUES (:producto, :monto, :usuario_id)";
        $stmt = $this->conn->prepare($query);

        // Limpieza básica de datos
        $pedido->producto = htmlspecialchars(strip_tags($pedido->producto));
        
        $stmt->bindParam(":producto", $pedido->producto);
        $stmt->bindParam(":monto", $pedido->monto);
        $stmt->bindParam(":usuario_id", $pedido->usuario_id);

        if ($stmt->execute()) {
            $pedido->id = $this->conn->lastInsertId();
            return $pedido;
        }
        return null;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM pedidos ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>