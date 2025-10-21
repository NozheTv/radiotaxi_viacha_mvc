<?php
class Geocerca {
    private $conn;
    private $table = "geocercas_tarifa";

    public $id;
    public $nombre_zona;
    public $poligono_geojson;
    public $tarifa_fija;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (nombre_zona, poligono_geojson, tarifa_fija) VALUES (:nombre, :poligono, :tarifa)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre_zona']);
        $stmt->bindParam(':poligono', $data['poligono_geojson']);
        $stmt->bindParam(':tarifa', $data['tarifa_fija']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET nombre_zona=:nombre, poligono_geojson=:poligono, tarifa_fija=:tarifa WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre_zona']);
        $stmt->bindParam(':poligono', $data['poligono_geojson']);
        $stmt->bindParam(':tarifa', $data['tarifa_fija']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
