<?php
class HistorialRuta {
    private $conn;
    private $table = "historial_rutas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener historial completo con datos de cliente y conductor desde pedidos y usuarios
    public function getHistorialCompleto() {
        $query = "
            SELECT hr.id, hr.detalles_ruta, hr.evaluacion_cliente, hr.evaluacion_conductor, hr.created_at,
                c.nombre AS cliente_nombre,
                co.nombre AS conductor_nombre
            FROM historial_rutas hr
            JOIN pedidos p ON hr.id_pedido = p.id
            JOIN usuarios c ON p.id_cliente = c.id
            LEFT JOIN usuarios co ON p.id_taxi = co.id
            ORDER BY hr.created_at DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalle específico de un historial por ID
    public function getHistorialPorId($id) {
        $query = "
            SELECT hr.*, 
                c.nombre AS cliente_nombre, c.correo AS cliente_email, c.telefono AS cliente_telefono,
                co.nombre AS conductor_nombre, co.correo AS conductor_email, co.telefono AS conductor_telefono
            FROM historial_rutas hr
            JOIN pedidos p ON hr.id_pedido = p.id
            JOIN usuarios c ON p.id_cliente = c.id
            LEFT JOIN usuarios co ON p.id_taxi = co.id
            WHERE hr.id = :id
            LIMIT 1
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
