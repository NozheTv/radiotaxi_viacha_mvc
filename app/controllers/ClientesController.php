<?php
require_once APP_ROOT . '/models/Usuario.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class ClientesController {

    private $usuarioModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->usuarioModel = new Usuario($db);
    }

    // Listar clientes
    public function index() {
        AuthController::checkAuth();

        $clientes = $this->usuarioModel->getClientes();
        require_once APP_ROOT . '/views/clientes/index.php';
    }

    // Formulario creación cliente
    public function create() {
        AuthController::checkAuth();
        require_once APP_ROOT . '/views/clientes/create.php';
    }

    // Guardar cliente nuevo
    public function store() {
    AuthController::checkAuth();

    $data = $_POST;

    // Validaciones básicas
    $errors = [];

    // Validar nombre
    if (empty($data['nombre']) || strlen($data['nombre']) > 40) {
        $errors[] = "El nombre es obligatorio y no debe exceder 40 caracteres.";
    }

    // Validar email
    if (empty($data['email']) || strlen($data['email']) > 40 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo electrónico es obligatorio, debe ser válido y no debe exceder 40 caracteres.";
    }

    // Validar teléfono (opcional)
    if (!empty($data['telefono'])) {
        // Solo números y max 15 caracteres
        if (!preg_match('/^\d{1,15}$/', $data['telefono'])) {
            $errors[] = "El teléfono debe contener solo números y máximo 15 dígitos.";
        }
    } else {
        // Si no viene teléfono, le pasamos null para evitar problemas en DB
        $data['telefono'] = null;
    }

    // Validar password
    if (empty($data['password']) || strlen($data['password']) > 30) {
        $errors[] = "La contraseña es obligatoria y no debe exceder 30 caracteres.";
    }

    // Si hay errores, puedes redirigir o mostrar errores
    if (!empty($errors)) {
        // Por ejemplo, pasar errores por sesión o query string y redirigir al formulario
        // Aquí uso query string simple
        $errorString = urlencode(implode(' | ', $errors));
        header('Location: ' . BASE_URL . 'clientes/create?error=' . $errorString);
        exit;
    }

    // Si pasó validación, crear cliente
    $this->usuarioModel->createCliente($data);

    // Redirigir a listado de clientes
    header('Location: ' . BASE_URL . 'clientes');
    exit;
}


    // Formulario edición cliente
    public function edit($id) {
        AuthController::checkAuth();

        $cliente = $this->usuarioModel->getClienteById($id);
        require_once APP_ROOT . '/views/clientes/edit.php';
    }

    // Actualizar cliente
    public function update($id) {
        AuthController::checkAuth();

        $data = $_POST;
        $this->usuarioModel->updateCliente($id, $data);
        header('Location: ' . BASE_URL . 'clientes');
        exit;
    }

    // Eliminar cliente
    public function delete($id) {
        AuthController::checkAuth();

        $this->usuarioModel->deleteCliente($id);
        header('Location: ' . BASE_URL . 'clientes');
        exit;
    }
}
