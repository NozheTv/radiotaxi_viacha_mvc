<?php
require_once APP_ROOT . 'models/Usuario.php';
require_once APP_ROOT . 'config/database.php';
require_once APP_ROOT . 'config/config.php';

class AuthController {
    private $db;
    private $usuarioModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuarioModel = new Usuario($this->db);
    }

    public function login($email, $password) {
        $stmt = $this->usuarioModel->login($email);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_rol'] = $user['rol'];
            redirect('admin/dashboard.php');
        } else {
            return "Usuario o contraseña incorrectos.";
        }
    }

    public function logout() {
        session_destroy();
        redirect('login.php');
    }

    public static function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            redirect('login.php');
        }
    }

    public static function isAdmin() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
    }
}
