<?php
// Definiciones de rutas absolutas para todo el proyecto
define('APP_ROOT', dirname(__DIR__));               // Ruta raíz del proyecto
define('PUBLIC_ROOT', __DIR__);                      // Carpeta public
define('BASE_URL', '/radiotaxi_viacha_mvc/public/'); // URL base para links

// Autocarga y configuración
require_once APP_ROOT . '/config/config.php';

// Parsear URL para enrutar
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$segments = explode('/', $url);

// Controlador, método y posibles parámetros
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
$method = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

$controllerFile = APP_ROOT . "/app/controllers/$controllerName.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName;
        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            http_response_code(404);
            echo "Error 404: Método '$method' no encontrado.";
        }
    }
} else {
    http_response_code(404);
    echo "Error 404: Controlador '$controllerName' no encontrado.";
}
