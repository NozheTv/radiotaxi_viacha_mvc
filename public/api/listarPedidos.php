<?php
require_once dirname(__DIR__, 2) . '/app/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Pedido.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$pedidoModel = new Pedido($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Si se pasa parámetro id, devolver detalles de pedido
        if (isset($_GET['id'])) {
            $pedido = $pedidoModel->getPedidoById(intval($_GET['id']));
            if ($pedido) {
                http_response_code(200);
                echo json_encode($pedido);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Pedido no encontrado']);
            }
        }
        // Si se pasa parámetro id_cliente, devolver pedidos de ese cliente
        else if (isset($_GET['id_cliente'])) {
            $pedidos = $pedidoModel->getPedidosByCliente(intval($_GET['id_cliente']));
            http_response_code(200);
            echo json_encode($pedidos);
        }
        // De lo contrario, listar todos los pedidos
        else {
            $query = "SELECT * FROM pedidos ORDER BY fecha_hora_solicitud DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode($pedidos);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}
