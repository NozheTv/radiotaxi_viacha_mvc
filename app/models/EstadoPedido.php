<?php
class EstadoPedido {
    private $conn;
    private $table = "estados_pedido";

    public $id;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarEstados() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadoById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
