<?php
require_once 'config/database.php';
require_once 'models/Usuario.php';

$database = new Database();
$db = $database->getConnection();

$usuarioModel = new Usuario($db);

// Verificar si ya existe un administrador
$query = "SELECT COUNT(*) FROM usuarios WHERE rol='admin'";
$stmt = $db->prepare($query);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo "Ya existe un administrador en el sistema.\n";
    exit;
}

// Crear administrador inicial
$usuarioModel->nombre = "Jesus Escobar";
$usuarioModel->email = "jescoar@radiotaxi.com";
$usuarioModel->password = "root"; // Cambiar por contraseña segura
$usuarioModel->rol = "admin";

if ($usuarioModel->create()) {
    echo "Administrador creado exitosamente.\n";
} else {
    echo "Error al crear administrador.\n";
}
