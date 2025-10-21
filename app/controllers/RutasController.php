<?php
require_once APP_ROOT . '/models/HistorialRuta.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class RutasController {
    private $historialRutaModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->historialRutaModel = new HistorialRuta($db);
    }

    // Listar historial de viajes con datos
    public function index() {
        AuthController::checkAuth();

        $historiales = $this->historialRutaModel->getHistorialCompleto();
        require_once APP_ROOT . '/views/rutas/index.php';
    }

    // Ver detalle del viaje específico
    public function detalle($id) {
        AuthController::checkAuth();

        $detalle = $this->historialRutaModel->getHistorialPorId($id);
        if (!$detalle) {
            header('Location: ' . BASE_URL . 'rutas');
            exit;
        }
        require_once APP_ROOT . '/views/rutas/detalle.php';
    }
}
