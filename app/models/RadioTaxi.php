<?php
class Radiotaxi {
    private $conn;
    private $table = "radiotaxis";

    public $id;
    public $placa;
    public $modelo;
    public $id_estado_taxi;
    public $gps_latitud;
    public $gps_longitud;
    public $id_conductor;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los taxis con estado y conductor
    public function getAll() {
        $query = "SELECT rt.*, et.descripcion AS estado_descripcion, u.nombre AS conductor_nombre 
                  FROM " . $this->table . " rt
                  LEFT JOIN estados_taxi et ON rt.id_estado_taxi = et.id
                  LEFT JOIN usuarios u ON rt.id_conductor = u.id
                  ORDER BY rt.placa ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id=:id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Asignar id_estado_taxi por defecto (buscar estado 'fuera de servicio')
        $estadoDefaultQuery = "SELECT id FROM estados_taxi WHERE descripcion = 'Fuera de Servicio' LIMIT 1";
        $estadoStmt = $this->conn->prepare($estadoDefaultQuery);
        $estadoStmt->execute();
        $estado = $estadoStmt->fetch(PDO::FETCH_ASSOC);
        $idEstado = $estado ? $estado['id'] : null;

        $query = "INSERT INTO " . $this->table . " 
            (placa, modelo, id_estado_taxi, gps_latitud, gps_longitud, id_conductor) 
            VALUES (:placa, :modelo, :id_estado_taxi, :gps_latitud, :gps_longitud, :id_conductor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":placa", $data['placa']);
        $stmt->bindParam(":modelo", $data['modelo']);
        $stmt->bindParam(":id_estado_taxi", $idEstado);
        $stmt->bindParam(":gps_latitud", $data['gps_latitud']);
        $stmt->bindParam(":gps_longitud", $data['gps_longitud']);
        $stmt->bindParam(":id_conductor", $data['id_conductor']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET 
            placa=:placa, modelo=:modelo, gps_latitud=:gps_latitud, gps_longitud=:gps_longitud, id_conductor=:id_conductor 
            WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":placa", $data['placa']);
        $stmt->bindParam(":modelo", $data['modelo']);
        $stmt->bindParam(":gps_latitud", $data['gps_latitud']);
        $stmt->bindParam(":gps_longitud", $data['gps_longitud']);
        $stmt->bindParam(":id_conductor", $data['id_conductor']);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Opcional: obtener lista de conductores para asignar
    public function getConductores() {
        $query = "SELECT id, nombre FROM usuarios WHERE rol = 'conductor' AND estado = 'activo' ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
