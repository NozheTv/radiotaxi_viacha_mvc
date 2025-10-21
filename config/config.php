<?php
session_start();

// Ruta base absoluta (ajustar según tu configuración de carpeta en XAMPP)
define('BASE_URL', 'http://localhost/radiotaxi_viacha_mvc/');

// Función de helper para redireccionar con base en ruta absoluta
function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit();
}
