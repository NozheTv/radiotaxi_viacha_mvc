<?php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Pedido.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$pedidoModel = new Pedido($db);

$method = $_SERVER['REQUEST_METHOD'];
if ($method != 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_pedido']) || !isset($data['id_taxi'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Faltan datos obligatorios: id_pedido, id_taxi']);
    exit;
}

$id_pedido = intval($data['id_pedido']);
$id_taxi = intval($data['id_taxi']);

// Asignar taxi y actualizar estado a "Asignado" (ejemplo estado ID = 2)
try {
    if ($pedidoModel->asignarTaxi($id_pedido, $id_taxi)) {
        $pedidoModel->actualizarEstado($id_pedido, 2);
        http_response_code(200);
        echo json_encode(['message' => 'Pedido aceptado y estado actualizado']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Error al aceptar pedido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Error en el servidor', 'error' => $e->getMessage()]);
}
