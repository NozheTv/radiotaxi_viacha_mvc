<?php

// Iniciar sesión
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Definir ruta raíz del proyecto (sin incluir "app" ni "public")
if(!defined('APP_ROOT')){
    define('APP_ROOT', dirname(__DIR__)); // Apunta a la carpeta radiotaxi_viacha_mvc
}

// Definir URL base para enlaces en la aplicación
if(!defined('BASE_URL')){
    define('BASE_URL', 'http://localhost/radiotaxi_viacha_mvc/public/');
}

// Definir ruta absoluta para la carpeta pública (public)
if(!defined('PUBLIC_ROOT')){
    define('PUBLIC_ROOT', APP_ROOT . '/public');
}

// Función helper para redireccionar a rutas absolutas
function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit();
}
