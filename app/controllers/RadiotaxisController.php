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

    public function index() {
        AuthController::checkAuth();

        $radiotaxis = $this->radiotaxiModel->getAll();
        require_once APP_ROOT . '/views/radiotaxis/index.php';
    }

    public function create() {
        AuthController::checkAuth();

        $conductores = $this->radiotaxiModel->getConductores();
        require_once APP_ROOT . '/views/radiotaxis/create.php';
    }

    public function store() {
        AuthController::checkAuth();

        $data = $_POST;
        $this->radiotaxiModel->create($data);
        header('Location: ' . BASE_URL . 'radiotaxis');
        exit;
    }

    public function edit($id) {
        AuthController::checkAuth();

        $radiotaxi = $this->radiotaxiModel->getById($id);
        $conductores = $this->radiotaxiModel->getConductores();
        require_once APP_ROOT . '/views/radiotaxis/edit.php';
    }

    public function update($id) {
        AuthController::checkAuth();

        $data = $_POST;
        $this->radiotaxiModel->update($id, $data);
        header('Location: ' . BASE_URL . 'radiotaxis');
        exit;
    }

    public function delete($id) {
        AuthController::checkAuth();

        $this->radiotaxiModel->delete($id);
        header('Location: ' . BASE_URL . 'radiotaxis');
        exit;
    }
}
