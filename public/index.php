<?php
require_once dirname(__DIR__) . '/app/config/config.php';

// Activar errores para debug (puedes desactivar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Variables para debug, inicialmente sin error
$debugErrors = [];

// Parsear URL amigable
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$segments = explode('/', $url);

// Determinar controlador, método y parámetros
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'AuthController';
$method = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// Ruta completa al archivo controlador
$controllerFile = APP_ROOT . "/controllers/$controllerName.php";

// Comprobar archivo controlador
if (!file_exists($controllerFile)) {
    $debugErrors[] = "Archivo controlador '$controllerFile' no encontrado.";
} else {
    require_once $controllerFile;

    if (!class_exists($controllerName)) {
        $debugErrors[] = "Clase '$controllerName' no definida en '$controllerFile'.";
    } else {
        $controller = new $controllerName;

        if (!method_exists($controller, $method)) {
            $debugErrors[] = "Método '$method' no encontrado en '$controllerName'.";
        } else {
            // No hay error, ejecutar método normalmente
            call_user_func_array([$controller, $method], $params);
        }
    }
}

// Mostrar debug solo si hay errores
if (!empty($debugErrors)) {
    echo "<h2>Debug MVC Router</h2>";
    echo "<p>Ruta solicitada: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "</p>";
    echo "<p>Controlador solicitado: <strong>$controllerName</strong></p>";
    echo "<p>Método solicitado: <strong>$method</strong></p>";
    echo "<p>Parámetros: <strong>" . json_encode($params) . "</strong></p>";
    echo "<h3>Errores encontrados:</h3><ul>";
    foreach ($debugErrors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}
