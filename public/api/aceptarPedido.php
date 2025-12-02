<?php
require_once dirname(__DIR__, 2) . '/app/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Pedido.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$pedidoModel = new Pedido($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Recibir datos JSON de la petición
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            !empty($data['id']) && 
            !empty($data['id_taxi'])
        ) {
            $pedidoId = intval($data['id']);
            $idTaxi = intval($data['id_taxi']);

            // Se puede incluir más validación según necesidades,
            // como comprobar si el pedido existe, etc.

            // Lógica para aceptar el pedido: actualizar id_taxi (conductor)
            // y cambiar estado del pedido a aceptado (ejemplo id_estado_pedido = 2)
            $estadoAceptado = 2;

            $query = "UPDATE pedidos SET id_taxi = :id_taxi, id_estado_pedido = :estado WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_taxi', $idTaxi, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estadoAceptado, PDO::PARAM_INT);
            $stmt->bindParam(':id', $pedidoId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['message' => 'Pedido aceptado correctamente', 'id_pedido' => $pedidoId, 'id_taxi' => $idTaxi]);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Error al actualizar el pedido']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Datos incompletos para aceptar el pedido']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}
