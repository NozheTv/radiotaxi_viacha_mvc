<?php
class Pedido {
    private $conn;
    private $table = "pedidos";

    public $id;
    public $id_cliente;
    public $id_taxi;
    public $origen_latitud;
    public $origen_longitud;
    public $destino_latitud;
    public $destino_longitud;
    public $tarifa;
    public $id_estado_pedido;
    public $prioridad;
    public $fecha_hora_solicitud;
    public $fecha_hora_inicio;
    public $fecha_hora_fin;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearPedido() {
        $query = "INSERT INTO {$this->table} 
            (id_cliente, origen_latitud, origen_longitud, destino_latitud, destino_longitud, tarifa, id_estado_pedido, prioridad, fecha_hora_solicitud) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $this->id_cliente, 
            $this->origen_latitud, 
            $this->origen_longitud, 
            $this->destino_latitud, 
            $this->destino_longitud, 
            $this->tarifa, 
            $this->id_estado_pedido,
            $this->prioridad ? 1 : 0
        ]);
    }

    public function actualizarEstado($id_pedido, $id_estado_pedido) {
        $query = "UPDATE {$this->table} SET id_estado_pedido = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_estado_pedido, $id_pedido]);
    }

    public function asignarTaxi($id_pedido, $id_taxi) {
        $query = "UPDATE {$this->table} SET id_taxi = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_taxi, $id_pedido]);
    }

    public function getPedidoById($id) {
        $query = "
            SELECT p.*, c.nombre AS nombre_cliente, e.descripcion AS estado_nombre, t.nombre AS nombre_conductor
            FROM {$this->table} p
            LEFT JOIN usuarios c ON p.id_cliente = c.id
            LEFT JOIN estados_pedido e ON p.id_estado_pedido = e.id
            LEFT JOIN usuarios t ON p.id_taxi = t.id
            WHERE p.id = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getPedidosByCliente($id_cliente) {
        $query = "SELECT * FROM {$this->table} WHERE id_cliente = ? ORDER BY fecha_hora_solicitud DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPedidosPendientesPrioridad() {
        $query = "SELECT * FROM {$this->table} WHERE prioridad = 1 AND id_estado_pedido = 1 ORDER BY fecha_hora_solicitud ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPedidosColaPorPrioridad() {
        $query = "
            SELECT p.*, 
                c.nombre AS nombre_cliente, 
                e.descripcion AS estado_nombre, 
                t.nombre AS nombre_conductor
            FROM {$this->table} p
            LEFT JOIN usuarios c ON p.id_cliente = c.id
            LEFT JOIN estados_pedido e ON p.id_estado_pedido = e.id
            LEFT JOIN usuarios t ON p.id_taxi = t.id
            ORDER BY p.prioridad DESC, p.fecha_hora_solicitud ASC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
