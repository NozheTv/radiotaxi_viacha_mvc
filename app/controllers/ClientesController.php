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
        $this->usuarioModel->createCliente($data);
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
