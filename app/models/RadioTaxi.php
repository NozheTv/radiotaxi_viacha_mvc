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
    try {
        // 1️⃣ Verificar si la placa ya existe
        $checkPlacaQuery = "SELECT COUNT(*) FROM {$this->table} WHERE placa = :placa";
        $checkPlacaStmt = $this->conn->prepare($checkPlacaQuery);
        $checkPlacaStmt->bindParam(':placa', $data['placa']);
        $checkPlacaStmt->execute();
        if ($checkPlacaStmt->fetchColumn() > 0) {
            throw new Exception("La placa '{$data['placa']}' ya está registrada. Usa otra diferente.");
        }

        // 2️⃣ Verificar si el conductor existe (solo si se proporciona un id_conductor)
        if (!empty($data['id_conductor'])) {
            $checkConductorQuery = "SELECT COUNT(*) FROM usuarios WHERE id = :id_conductor AND rol = 'conductor'";
            $checkConductorStmt = $this->conn->prepare($checkConductorQuery);
            $checkConductorStmt->bindParam(':id_conductor', $data['id_conductor']);
            $checkConductorStmt->execute();

            if ($checkConductorStmt->fetchColumn() == 0) {
                throw new Exception("El conductor seleccionado no existe o no es válido.");
            }
        }

        // 3️⃣ Obtener el ID del estado por defecto ('Fuera de Servicio')
        $estadoDefaultQuery = "SELECT id FROM estados_taxi WHERE descripcion = 'Fuera de Servicio' LIMIT 1";
        $estadoStmt = $this->conn->prepare($estadoDefaultQuery);
        $estadoStmt->execute();
        $estado = $estadoStmt->fetch(PDO::FETCH_ASSOC);
        $idEstado = $estado ? $estado['id'] : null;

        // 4️⃣ Preparar e insertar el nuevo registro
        $query = "INSERT INTO {$this->table} 
                  (placa, modelo, id_estado_taxi, gps_latitud, gps_longitud, id_conductor) 
                  VALUES (:placa, :modelo, :id_estado_taxi, :gps_latitud, :gps_longitud, :id_conductor)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':placa', $data['placa']);
        $stmt->bindParam(':modelo', $data['modelo']);
        $stmt->bindParam(':id_estado_taxi', $idEstado);
        $stmt->bindParam(':gps_latitud', $data['gps_latitud']);
        $stmt->bindParam(':gps_longitud', $data['gps_longitud']);
        $stmt->bindParam(':id_conductor', $data['id_conductor']);

        // 5️⃣ Ejecutar la inserción
        $stmt->execute();
        return true;

        } catch (Exception $e) {
            // 6️⃣ Lanzar el error al controlador (para mostrarlo en la vista)
            throw new Exception($e->getMessage());
        } catch (PDOException $e) {
            // Captura errores SQL (FK, duplicados, etc.)
            throw new Exception("Error al registrar el taxi: " . $e->getMessage());
        }
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
        $query = "SELECT id, nombre AS nombre_completo 
                FROM usuarios 
                WHERE rol = 'conductor' AND estado = 'activo' 
                ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
