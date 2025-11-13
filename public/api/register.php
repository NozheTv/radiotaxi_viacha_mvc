<?php
require_once dirname(__DIR__, 2) . '/app/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Usuario.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$usuarioModel = new Usuario($db);

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (
    empty($data['nombre']) || empty($data['email']) || empty($data['password']) ||
    empty($data['telefono'])
) {
    http_response_code(400);
    echo json_encode(['message' => 'Nombre, email, teléfono y password son requeridos']);
    exit;
}

try {
    // Preparar datos para crear usuario
    $dataToCreate = [
        'nombre' => $data['nombre'],
        'email' => $data['email'],
        'telefono' => $data['telefono'],
        'direccion' => isset($data['direccion']) ? $data['direccion'] : null,
        'password' => $data['password'],
        'plataforma_acceso' => 'app_cliente',  // fijado como en la vista
        'rol' => 'cliente',
        'estado' => 'activo'
    ];

    // Cambiar createUsuario para admitir dirección y plataforma
    $created = $usuarioModel->createUsuarioComplete($dataToCreate);

    if ($created) {
        http_response_code(201);
        echo json_encode(['message' => 'Cliente creado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Error al crear cliente']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['message' => $e->getMessage()]);
}
