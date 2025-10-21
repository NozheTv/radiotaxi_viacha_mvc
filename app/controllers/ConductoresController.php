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

    public function index() {
        AuthController::checkAuth();

        // Obtener conductores activos
        $conductores = $this->usuarioModel->getConductores();
        require_once APP_ROOT . '/views/conductores/index.php';
    }

    public function create() {
        AuthController::checkAuth();
        require_once APP_ROOT . '/views/conductores/create.php';
    }

    public function store() {
        AuthController::checkAuth();

        $data = $_POST;
        $this->usuarioModel->createConductor($data);
        header('Location: ' . BASE_URL . 'conductores');
        exit;
    }

    public function edit($id) {
        AuthController::checkAuth();

        $conductor = $this->usuarioModel->getConductorById($id);
        require_once APP_ROOT . '/views/conductores/edit.php';
    }

    public function update($id) {
        AuthController::checkAuth();

        $data = $_POST;
        $this->usuarioModel->updateConductor($id, $data);
        header('Location: ' . BASE_URL . 'conductores');
        exit;
    }

    public function delete($id) {
        AuthController::checkAuth();

        $this->usuarioModel->deleteConductor($id);
        header('Location: ' . BASE_URL . 'conductores');
        exit;
    }
}
