<?php
require_once APP_ROOT . '/models/Usuario.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/config/config.php';

class AuthController {
    private $db;
    private $usuarioModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuarioModel = new Usuario($this->db);
    }

    // Mostrar formulario login
    public function index() {
        require_once APP_ROOT . '/views/admin/login.php';
    }

    // Procesar login, toma datos desde POST
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $errorMessage = "Debe ingresar correo y contraseña.";
            require_once APP_ROOT . '/views/admin/login.php';
            return;
        }

        $stmt = $this->usuarioModel->login($email);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_rol'] = $user['rol'];
            redirect('admin/dashboard');
        } else {
            $errorMessage = "Usuario o contraseña incorrectos.";
            require_once APP_ROOT . '/views/admin/login.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('auth');
    }

    public static function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            redirect('auth');
        }
    }

    public static function isAdmin() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
    }
}
