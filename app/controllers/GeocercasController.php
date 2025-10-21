<?php
require_once APP_ROOT . '/models/Geocerca.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class GeocercasController {
    private $geocercaModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->geocercaModel = new Geocerca($db);
    }

    public function index() {
        AuthController::checkAuth();

        $geocercas = $this->geocercaModel->getAll();
        require_once APP_ROOT . '/views/geocercas/index.php';
    }

    public function create() {
        AuthController::checkAuth();

        require_once APP_ROOT . '/views/geocercas/create.php';
    }

    public function store() {
        AuthController::checkAuth();

        $data = $_POST;
        if ($this->geocercaModel->create($data)) {
            header('Location: ' . BASE_URL . 'geocercas');
            exit;
        } else {
            echo "Error al crear geocerca";
        }
    }

    public function edit($id) {
        AuthController::checkAuth();

        $geocerca = $this->geocercaModel->getById($id);
        require_once APP_ROOT . '/views/geocercas/edit.php';
    }

    public function update($id) {
        AuthController::checkAuth();

        $data = $_POST;
        if ($this->geocercaModel->update($id, $data)) {
            header('Location: ' . BASE_URL . 'geocercas');
            exit;
        } else {
            echo "Error al actualizar geocerca";
        }
    }
}
