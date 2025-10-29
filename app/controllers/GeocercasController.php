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
    public function checkName() {
        $nombre = $_GET['nombre'] ?? '';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        header('Content-Type: application/json');
        if (empty($nombre)) {
            echo json_encode(['exists' => false]);
            return;
        }

        $stmt = $this->geocercaModel->checkNameExists($nombre, $id);
        echo json_encode(['exists' => $stmt]);
    }

    public function create() {
        AuthController::checkAuth();

        require_once APP_ROOT . '/views/geocercas/create.php';
    }

    public function store() {
        AuthController::checkAuth();

        $data = $_POST;

        // Verificar si ya existe una geocerca con ese nombre (en el backend)
        if ($this->geocercaModel->checkNameExists($data['nombre_zona'])) {
            echo "<script>
                    alert('❌ Ya existe una geocerca con ese nombre.');
                    window.history.back();
                </script>";
            exit;
        }

        // Si no existe, la crea normalmente
        if ($this->geocercaModel->create($data)) {
            header('Location: ' . BASE_URL . 'geocercas');
            exit;
        } else {
            echo "<script>
                    alert('⚠️ Error al crear la geocerca.');
                    window.history.back();
                </script>";
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
