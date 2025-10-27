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

    public function store() {
        AuthController::checkAuth();
        $data = $_POST;

        // Validaciones básicas
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

        // Si hay errores, redirigir con mensaje y conservar datos
        if (!empty($errors)) {
            $query = http_build_query(array_merge(['error' => implode(' | ', $errors)], $data));
            header('Location: ' . BASE_URL . 'conductores/create?' . $query);
            exit;
        }

        // Verificar si el correo ya está registrado
        $existingUser = $this->usuarioModel->getByEmail($data['email']);
        if ($existingUser) {
            $query = http_build_query(array_merge(['error' => 'El correo ya está registrado'], $data));
            header('Location: ' . BASE_URL . 'conductores/create?' . $query);
            exit;
        }

        // Intentar crear el conductor
        try {
            $this->usuarioModel->createConductor($data);
            header('Location: ' . BASE_URL . 'conductores?success=' . urlencode('Conductor creado exitosamente'));
            exit;
        } catch (Exception $e) {
            // Pasar mensaje de error y datos para mantener el formulario con valores ingresados
            $query = http_build_query(array_merge(['error' => $e->getMessage()], $data));
            header('Location: ' . BASE_URL . 'conductores/create?' . $query);
            exit;
        }
    }

    // Formulario edición conductor
    public function edit($id) {
        AuthController::checkAuth();

        $conductor = $this->usuarioModel->getConductorById($id);

        if (!$conductor) {
            // Redirige si no existe el conductor
            header('Location: ' . BASE_URL . 'conductores?error=' . urlencode('Conductor no encontrado'));
            exit;
        }

        require_once APP_ROOT . '/views/conductores/edit.php';
    }

    // Actualizar conductor
    public function update($id) {
        AuthController::checkAuth();

        $data = $_POST;

        // Validaciones similares a store para mantener consistencia
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

        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8 || strlen($data['password']) > 15) {
                $errors[] = "La contraseña debe tener entre 8 y 15 caracteres si se proporciona.";
            }
        }

        if (!empty($errors)) {
            // Redirige a edit con errores y datos para rellenar formulario
            $query = http_build_query(array_merge(['error' => implode(' | ', $errors)], $data));
            header('Location: ' . BASE_URL . 'conductores/edit/' . $id . '?' . $query);
            exit;
        }

        // Verificar si el email está registrado en otro usuario distinto (para evitar conflicto)
        $existingUser = $this->usuarioModel->getByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != $id) {
            $query = http_build_query(array_merge(['error' => 'El correo ya está registrado en otro usuario.'], $data));
            header('Location: ' . BASE_URL . 'conductores/edit/' . $id . '?' . $query);
            exit;
        }

        // Intentar actualizar el conductor
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
