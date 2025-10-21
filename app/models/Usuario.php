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

    public function login($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt;
    }

    public function getClientes() {
        $query = "SELECT * FROM usuarios WHERE rol = 'cliente' AND estado = 'activo' ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClienteById($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id AND rol = 'cliente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCliente($data) {
        $query = "INSERT INTO usuarios (nombre, email, telefono, rol, password, estado) VALUES (:nombre, :email, :telefono, 'cliente', :password, 'activo')";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
    }

    public function updateCliente($id, $data) {
        $query = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id AND rol = 'cliente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function deleteCliente($id) {
        $query = "UPDATE usuarios SET estado = 'inactivo' WHERE id = :id AND rol = 'cliente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET nombre=:nombre, email=:email, password=:password, rol=:rol, estado='activo'";
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
        $query = "SELECT id, nombre, email, rol, estado FROM " . $this->table . " WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT id, nombre, email, rol, estado FROM " . $this->table . " WHERE id = ? AND estado = 'activo' LIMIT 0,1";
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
        $query = "UPDATE " . $this->table . " SET estado = 'inactivo' WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
