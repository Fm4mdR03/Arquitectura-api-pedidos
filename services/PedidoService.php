<?php
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../repositories/PedidoRepository.php';

class PedidoService {
    private $usuarioRepo;
    private $pedidoRepo;

    public function __construct($db) {
        // Inyección de dependencias manual
        $this->usuarioRepo = new UsuarioRepository($db);
        $this->pedidoRepo = new PedidoRepository($db);
    }

    public function crearPedido($datos) {
        // 1. Validar que el usuario exista
        if (!isset($datos->usuario_id) || !isset($datos->producto) || !isset($datos->monto)) {
            throw new Exception("Datos incompletos. Se requiere usuario_id, producto y monto.");
        }

        $usuario = $this->usuarioRepo->obtenerPorId($datos->usuario_id);
        
        if (!$usuario) {
            // Si el usuario no existe, se detiene el proceso (Lógica SOA)
            throw new Exception("El usuario con ID " . $datos->usuario_id . " no existe. Pedido rechazado.");
        }

        // 2. Si pasa la validación, preparamos la entidad
        $nuevoPedido = new Pedido();
        $nuevoPedido->producto = $datos->producto;
        $nuevoPedido->monto = $datos->monto;
        $nuevoPedido->usuario_id = $datos->usuario_id;

        // 3. Llamamos al repositorio para persistir
        $resultado = $this->pedidoRepo->guardar($nuevoPedido);
        
        if (!$resultado) {
            throw new Exception("Error interno al guardar en base de datos.");
        }

        return $resultado;
    }

    public function listarPedidos() {
        return $this->pedidoRepo->obtenerTodos();
    }
}
?>