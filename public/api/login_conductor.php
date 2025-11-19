<?php
require_once dirname(__DIR__, 2) . '/app/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Usuario.php';

// Respuesta JSON
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

if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Email y password son requeridos']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Obtener usuario por email
$user = $usuarioModel->login($email);

// Validar usuario, rol y estado (solo conductores activos pueden ingresar)
if (!$user || $user['rol'] !== 'conductor' || $user['estado'] !== 'activo') {
    http_response_code(401);
    echo json_encode(['message' => 'Credenciales inválidas']);
    exit;
}

// Verificar contraseña
if (password_verify($password, $user['password'])) {
    // Eliminar contraseña del array de respuesta
    unset($user['password']);
    http_response_code(200);
    echo json_encode(['message' => 'Login exitoso', 'user' => $user]);
} else {
    http_response_code(401);
    echo json_encode(['message' => 'Credenciales inválidas']);
}
