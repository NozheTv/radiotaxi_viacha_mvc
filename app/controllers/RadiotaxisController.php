<?php
require_once APP_ROOT . '/models/Radiotaxi.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class RadiotaxisController {
    private $radiotaxiModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->radiotaxiModel = new Radiotaxi($db);
    }

    // 📋 Listar todos los radiotaxis
    public function index() {
        AuthController::checkAuth();

        $radiotaxis = $this->radiotaxiModel->getAll();
        require_once APP_ROOT . '/views/radiotaxis/index.php';
    }

    // 🚗 Mostrar formulario de creación
    public function create() {
        AuthController::checkAuth();

        $conductores = $this->radiotaxiModel->getConductores();
        require_once APP_ROOT . '/views/radiotaxis/create.php';
    }

    // 💾 Guardar nuevo taxi
    public function store() {
        AuthController::checkAuth();
        $data = $_POST;

        $errors = [];

        // --- VALIDACIONES ---
        if (empty($data['placa']) || strlen($data['placa']) > 10) {
            $errors[] = "La placa es obligatoria y no debe exceder 10 caracteres.";
        }

        if (empty($data['modelo']) || strlen($data['modelo']) > 40) {
            $errors[] = "El modelo es obligatorio y no debe exceder 40 caracteres.";
        }

        // Validar coordenadas si vienen
        if (!empty($data['gps_latitud']) && !is_numeric($data['gps_latitud'])) {
            $errors[] = "La latitud debe ser un número válido.";
        }

        if (!empty($data['gps_longitud']) && !is_numeric($data['gps_longitud'])) {
            $errors[] = "La longitud debe ser un número válido.";
        }

        // Validar conductor si viene
        if (!empty($data['id_conductor']) && !is_numeric($data['id_conductor'])) {
            $errors[] = "El ID del conductor no es válido.";
        }

        // Si hay errores, redirigir con mensaje
        if (!empty($errors)) {
            $errorString = urlencode(implode(' | ', $errors));
            header('Location: ' . BASE_URL . 'radiotaxis/create?error=' . $errorString);
            exit;
        }

        // Intentar crear taxi
        try {
            $this->radiotaxiModel->create($data);
            header('Location: ' . BASE_URL . 'radiotaxis?success=' . urlencode('Radiotaxi registrado correctamente.'));
            exit;

        } catch (Exception $e) {
            header('Location: ' . BASE_URL . 'radiotaxis/create?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // ✏️ Formulario edición de taxi
    public function edit($id) {
        AuthController::checkAuth();

        $radiotaxi = $this->radiotaxiModel->getById($id);
        $conductores = $this->radiotaxiModel->getConductores();
        require_once APP_ROOT . '/views/radiotaxis/edit.php';
    }

    // 🔄 Actualizar taxi existente
    public function update($id) {
        AuthController::checkAuth();
        $data = $_POST;

        try {
            $this->radiotaxiModel->update($id, $data);
            header('Location: ' . BASE_URL . 'radiotaxis?success=' . urlencode('Radiotaxi actualizado correctamente.'));
            exit;

        } catch (Exception $e) {
            header('Location: ' . BASE_URL . 'radiotaxis/edit/' . $id . '?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // 🗑️ Eliminar taxi
    public function delete($id) {
        AuthController::checkAuth();

        try {
            $this->radiotaxiModel->delete($id);
            header('Location: ' . BASE_URL . 'radiotaxis?success=' . urlencode('Radiotaxi eliminado correctamente.'));
            exit;

        } catch (Exception $e) {
            header('Location: ' . BASE_URL . 'radiotaxis?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
