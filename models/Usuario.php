<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol; // admin, conductor, cliente

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET nombre=:nombre, email=:email, password=:password, rol=:rol";
        $stmt = $this->conn->prepare($query);

        // Encriptar password
        $hash = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hash);
        $stmt->bindParam(":rol", $this->rol);

        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT id, nombre, email, rol FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT id, nombre, email, rol FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->rol = $row['rol'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET nombre=:nombre, email=:email, rol=:rol";
        if (!empty($this->password)) {
            $query .= ", password=:password";
        }
        $query .= " WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":rol", $this->rol);
        if (!empty($this->password)) {
            $hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $hash);
        }
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
