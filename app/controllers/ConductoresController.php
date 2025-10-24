<?php
require_once APP_ROOT . '/models/Usuario.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class ConductoresController {

    private $usuarioModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->usuarioModel = new Usuario($db);
    }

    // Listar conductores
    public function index() {
        AuthController::checkAuth();

        $conductores = $this->usuarioModel->getConductores();
        require_once APP_ROOT . '/views/conductores/index.php';
    }

    // Formulario creación de conductor
    public function create() {
        AuthController::checkAuth();
        require_once APP_ROOT . '/views/conductores/create.php';
    }

    // Guardar conductor nuevo
    public function store() {
        AuthController::checkAuth();
        $data = $_POST;

        // Validaciones básicas (puedes agregar más si quieres)
        $errors = [];

        if (empty($data['nombre']) || strlen($data['nombre']) > 40) {
            $errors[] = "El nombre es obligatorio y no debe exceder 40 caracteres.";
        }

        if (empty($data['email']) || strlen($data['email']) > 40 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo electrónico es obligatorio, debe ser válido y no debe exceder 40 caracteres.";
        }

        if (!empty($data['telefono'])) {
            if (!preg_match('/^\d{1,15}$/', $data['telefono'])) {
                $errors[] = "El teléfono debe contener solo números y máximo 15 dígitos.";
            }
        } else {
            $data['telefono'] = null;
        }

        if (empty($data['password']) || strlen($data['password']) < 8 || strlen($data['password']) > 15) {
            $errors[] = "La contraseña es obligatoria y debe tener entre 8 y 15 caracteres.";
        }

        if (!empty($errors)) {
            $errorString = urlencode(implode(' | ', $errors));
            header('Location: ' . BASE_URL . 'conductores/create?error=' . $errorString);
            exit;
        }

        // Intentar crear conductor y capturar errores de duplicados
        try {
            $this->usuarioModel->createConductor($data);
            header('Location: ' . BASE_URL . 'conductores?success=' . urlencode('Conductor creado exitosamente'));
            exit;
        } catch (Exception $e) {
            header('Location: ' . BASE_URL . 'conductores/create?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // Formulario edición conductor
    public function edit($id) {
        AuthController::checkAuth();

        $conductor = $this->usuarioModel->getConductorById($id);
        require_once APP_ROOT . '/views/conductores/edit.php';
    }

    // Actualizar conductor
    public function update($id) {
        AuthController::checkAuth();

        $data = $_POST;

        try {
            $this->usuarioModel->updateConductor($id, $data);
            header('Location: ' . BASE_URL . 'conductores?success=' . urlencode('Conductor actualizado correctamente'));
            exit;
        } catch (Exception $e) {
            header('Location: ' . BASE_URL . 'conductores/edit/' . $id . '?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // Eliminar conductor
    public function delete($id) {
        AuthController::checkAuth();

        try {
            $this->usuarioModel->deleteConductor($id);
            header('Location: ' . BASE_URL . 'conductores?success=' . urlencode('Conductor eliminado correctamente'));
            exit;
        } catch (Exception $e) {
            header('Location: ' . BASE_URL . 'conductores?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
