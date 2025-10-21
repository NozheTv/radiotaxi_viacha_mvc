<?php
class ColaPrioridad {
    private $conn;
    private $table = "colas_prioridad";

    public $id;
    public $id_pedido;
    public $fecha_hora_insercion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insertar un pedido en la cola de prioridad
    public function insertarEnCola($id_pedido) {
        $query = "INSERT INTO {$this->table} (id_pedido, fecha_hora_insercion) VALUES (?, NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_pedido]);
    }

    // Obtener pedidos en cola ordenados por fecha_hora_insercion ascendente (los más antiguos primero)
    public function obtenerPedidosEnCola() {
        $query = "SELECT c.*, p.* FROM {$this->table} c 
                  JOIN pedidos p ON c.id_pedido = p.id
                  ORDER BY c.fecha_hora_insercion ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar pedido de la cola (por ejemplo, cuando se asigna el taxi)
    public function eliminarDeCola($id_pedido) {
        $query = "DELETE FROM {$this->table} WHERE id_pedido = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_pedido]);
    }

    // Verificar si un pedido ya está en cola
    public function estaEnCola($id_pedido) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE id_pedido = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_pedido]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
}
?>
