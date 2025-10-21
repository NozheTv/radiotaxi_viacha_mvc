<?php
require_once APP_ROOT . '/models/Usuario.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class AdminController {
    private $db;
    private $usuarioModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuarioModel = new Usuario($this->db);
    }

    // Lista todos los usuarios
    public function index() {
        $stmt = $this->usuarioModel->readAll();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $usuarios;
    }

    public function dashboard() {
        // Evitar cache para evitar acceso tras logout con botón atrás
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        AuthController::checkAuth();
        require_once APP_ROOT . '/views/admin/dashboard.php';
    }

    public function clientes() {
        header('Location: ' . BASE_URL . 'clientes');
        exit;
    }
    public function conductores() {
        header('Location: ' . BASE_URL . 'conductores');
        exit;
    }
    public function rutas() {
        header('Location: ' . BASE_URL . 'rutas');
        exit;
    }





    // Mostrar usuario por ID
    public function show($id) {
        $this->usuarioModel->id = $id;
        if ($this->usuarioModel->readOne()) {
            return [
                'id' => $this->usuarioModel->id,
                'nombre' => $this->usuarioModel->nombre,
                'email' => $this->usuarioModel->email,
                'rol' => $this->usuarioModel->rol,
            ];
        }
        return null; // no encontrado
    }

    // Crear nuevo usuario
    public function create($data) {
        $this->usuarioModel->nombre = $data['nombre'];
        $this->usuarioModel->email = $data['email'];
        $this->usuarioModel->password = $data['password'];
        $this->usuarioModel->rol = $data['rol'];
        return $this->usuarioModel->create();
    }

    // Actualizar usuario
    public function update($id, $data) {
        $this->usuarioModel->id = $id;
        $this->usuarioModel->nombre = $data['nombre'];
        $this->usuarioModel->email = $data['email'];
        $this->usuarioModel->rol = $data['rol'];
        if (!empty($data['password'])) {
            $this->usuarioModel->password = $data['password'];
        } else {
            $this->usuarioModel->password = null;
        }
        return $this->usuarioModel->update();
    }

    // Eliminar usuario
    public function delete($id) {
        $this->usuarioModel->id = $id;
        return $this->usuarioModel->delete();
    }

    // Función para validar si el usuario es admin (puedes mejorarla para sesiones reales)
    public function isAdmin($userRole) {
        return $userRole === 'admin';
    }
}
