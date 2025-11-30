<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");

require_once 'config/Database.php';
require_once 'services/PedidoService.php';

// Inicializar conexión y servicio
$database = new Database();
$db = $database->getConnection();
$pedidoService = new PedidoService($db);

$metodo = $_SERVER['REQUEST_METHOD'];

// Enrutador simple
switch ($metodo) {
    case 'GET':
        // Endpoint: Listar pedidos
        try {
            $pedidos = $pedidoService->listarPedidos();
            echo json_encode($pedidos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["mensaje" => $e->getMessage()]);
        }
        break;

    case 'POST':
        // Endpoint: Crear pedido
        $data = json_decode(file_get_contents("php://input"));
        
        try {
            $pedidoCreado = $pedidoService->crearPedido($data);
            http_response_code(201); // Created
            echo json_encode([
                "mensaje" => "Pedido creado exitosamente",
                "datos" => $pedidoCreado
            ]);
        } catch (Exception $e) {
            // Manejo de errores de negocio (Bad Request)
            http_response_code(400); 
            echo json_encode(["error" => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}
?>