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
        $errors = [];

        // Validaciones básicas
        if (empty($data['nombre']) || strlen($data['nombre']) > 40) {
            $errors[] = "El nombre es obligatorio y no debe exceder 40 caracteres.";
        }

        if (empty($data['email']) || strlen($data['email']) > 40 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo electrónico es obligatorio, debe ser válido y no debe exceder 40 caracteres.";
        }

        if (!empty($data['telefono']) && !preg_match('/^\d{1,15}$/', $data['telefono'])) {
            $errors[] = "El teléfono debe contener solo números y máximo 15 dígitos.";
        } elseif (empty($data['telefono'])) {
            $data['telefono'] = null;
        }

        if (empty($data['password']) || strlen($data['password']) > 30) {
            $errors[] = "La contraseña es obligatoria y no debe exceder 30 caracteres.";
        }

        // Si hay errores de validación, redirigir con mensaje
        if (!empty($errors)) {
            $errorString = urlencode(implode(' | ', $errors));
            header('Location: ' . BASE_URL . 'clientes/create?error=' . $errorString);
            exit;
        }

        // Intentar crear cliente y manejar posibles excepciones (correo duplicado, DB, etc.)
        try {
            $this->usuarioModel->createCliente($data);

            // Éxito
            header('Location: ' . BASE_URL . 'clientes?success=' . urlencode("Cliente registrado correctamente."));
            exit;

        } catch (Exception $e) {
            // Error al crear cliente (correo duplicado u otro)
            $errorMsg = $e->getMessage();
            header('Location: ' . BASE_URL . 'clientes/create?error=' . urlencode($errorMsg));
            exit;
        }
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

        try {
            $this->usuarioModel->updateCliente($id, $data);
            header('Location: ' . BASE_URL . 'clientes?success=' . urlencode("Cliente actualizado correctamente."));
            exit;
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            header('Location: ' . BASE_URL . 'clientes/edit/' . $id . '?error=' . urlencode($errorMsg));
            exit;
        }
    }

    // Eliminar cliente
    public function delete($id) {
        AuthController::checkAuth();
        try {
            $this->usuarioModel->deleteCliente($id);
            header('Location: ' . BASE_URL . 'clientes?success=' . urlencode("Cliente eliminado correctamente."));
            exit;
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            header('Location: ' . BASE_URL . 'clientes?error=' . urlencode($errorMsg));
            exit;
        }
    }
}
