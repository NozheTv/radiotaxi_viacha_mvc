<?php
require_once dirname(__DIR__, 2) . '/app/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Pedido.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$pedidoModel = new Pedido($db);

$method = $_SERVER['REQUEST_METHOD'];

// Función para ordenar pedidos según prioridad y fecha
function ordenarPedidosPorPrioridad(array $pedidos): array {
    // Filtrar sólo los pedidos cuyo estado sea "pendiente" (id_estado_pedido === 1)
    $pedidosPendientes = array_filter($pedidos, function($pedido) {
        return $pedido['id_estado_pedido'] === 1;
    });

    // Ordenar los pedidos pendientes por prioridad descendente y fecha ascendente
    usort($pedidosPendientes, function($a, $b) {
        if ($a['prioridad'] > $b['prioridad']) return -1;
        if ($a['prioridad'] < $b['prioridad']) return 1;

        $fechaA = new DateTime($a['fecha_hora_solicitud']);
        $fechaB = new DateTime($b['fecha_hora_solicitud']);

        if ($fechaA < $fechaB) return -1;
        if ($fechaA > $fechaB) return 1;

        return 0;
    });

    return $pedidosPendientes;
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener un pedido específico por ID
            $pedido = $pedidoModel->getPedidoById(intval($_GET['id']));
            if ($pedido) {
                // Formatear respuesta con datos para el mapa
                $respuesta = [
                    'id' => $pedido['id'],
                    'id_cliente' => $pedido['id_cliente'],
                    'origen' => [
                        'latitud' => (float) $pedido['origen_latitud'],
                        'longitud' => (float) $pedido['origen_longitud']
                    ],
                    'destino' => [
                        'latitud' => (float) $pedido['destino_latitud'],
                        'longitud' => (float) $pedido['destino_longitud']
                    ],
                    'tarifa' => (float) $pedido['tarifa'],
                    'id_estado_pedido' => $pedido['id_estado_pedido'],
                    'prioridad' => $pedido['prioridad'],
                    'fecha_hora_solicitud' => $pedido['fecha_hora_solicitud']
                ];
                http_response_code(200);
                echo json_encode($respuesta);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Pedido no encontrado']);
            }
        } else if (isset($_GET['id_cliente'])) {
            // Obtener pedidos por cliente
            $pedidos = $pedidoModel->getPedidosByCliente(intval($_GET['id_cliente']));
            $pedidosFormateados = array_map(function($pedido) {
                return [
                    'id' => $pedido['id'],
                    'id_cliente' => $pedido['id_cliente'],
                    'origen' => [
                        'latitud' => (float) $pedido['origen_latitud'],
                        'longitud' => (float) $pedido['origen_longitud']
                    ],
                    'destino' => [
                        'latitud' => (float) $pedido['destino_latitud'],
                        'longitud' => (float) $pedido['destino_longitud']
                    ],
                    'tarifa' => (float) $pedido['tarifa'],
                    'id_estado_pedido' => $pedido['id_estado_pedido'],
                    'prioridad' => $pedido['prioridad'],
                    'fecha_hora_solicitud' => $pedido['fecha_hora_solicitud']
                ];
            }, $pedidos);

            http_response_code(200);
            echo json_encode($pedidosFormateados);
        } else {
            // Listar pedidos pendientes ordenados por prioridad y fecha
            $query = "SELECT * FROM pedidos ORDER BY fecha_hora_solicitud DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $pedidosOrdenados = ordenarPedidosPorPrioridad($pedidos);

            // Formatear para el mapa
            $pedidosParaMapa = array_map(function($pedido) {
                return [
                    'id' => $pedido['id'],
                    'id_cliente' => $pedido['id_cliente'],
                    'origen' => [
                        'latitud' => (float) $pedido['origen_latitud'],
                        'longitud' => (float) $pedido['origen_longitud']
                    ],
                    'destino' => [
                        'latitud' => (float) $pedido['destino_latitud'],
                        'longitud' => (float) $pedido['destino_longitud']
                    ],
                    'tarifa' => (float) $pedido['tarifa'],
                    'id_estado_pedido' => $pedido['id_estado_pedido'],
                    'prioridad' => $pedido['prioridad'],
                    'fecha_hora_solicitud' => $pedido['fecha_hora_solicitud']
                ];
            }, $pedidosOrdenados);

            http_response_code(200);
            echo json_encode($pedidosParaMapa);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}
