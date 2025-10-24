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

    public function createCliente($data)
    {
        try {
            // 1️⃣ Verificar si el correo ya existe
            $checkQuery = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':email', $data['email']);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                // Si el correo ya está registrado, lanzamos una excepción personalizada
                throw new Exception("El correo ya está registrado. Por favor usa otro correo electrónico.");
            }

            // 2️⃣ Insertar nuevo cliente si el correo no existe
            $query = "INSERT INTO usuarios (nombre, email, telefono, rol, password, estado)
                    VALUES (:nombre, :email, :telefono, 'cliente', :password, 'activo')";
            $stmt = $this->conn->prepare($query);

            // Encriptar la contraseña
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Vincular los parámetros
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':password', $hashedPassword);

            // Ejecutar
            $stmt->execute();

            return true; // opcional: puedes usarlo para verificar si se insertó correctamente

        } catch (PDOException $e) {
            // 3️⃣ Manejar error PDO (como duplicado o problema de conexión)
            if ($e->getCode() == 23000) {
                throw new Exception("Error: El correo ya está registrado en el sistema.");
            } else {
                throw new Exception("Error en la base de datos: " . $e->getMessage());
            }
        } catch (Exception $e) {
            // 4️⃣ Capturar y propagar cualquier otra excepción
            throw $e;
        }
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

    // Obtener conductores activos
    public function getConductores() {
        $query = "SELECT * FROM usuarios WHERE rol = 'conductor' AND estado = 'activo' ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener conductor por id
    public function getConductorById($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id AND rol = 'conductor'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear conductor nuevo
    public function createConductor($data) {
        // 1. Verificar si el correo ya existe
        $queryCheck = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':email', $data['email']);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            // Lanza una excepción si ya existe
            throw new Exception("El correo ya está registrado. Por favor usa otro correo electrónico.");
        }

        // 2. Insertar el conductor si el correo no existe
        $query = "INSERT INTO usuarios (nombre, email, telefono, rol, password, estado) 
                VALUES (:nombre, :email, :telefono, 'conductor', :password, 'activo')";
        $stmt = $this->conn->prepare($query);

        // Encriptar la contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();
    }


    // Actualizar conductor
    public function updateConductor($id, $data) {
        $query = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id AND rol = 'conductor'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Eliminar conductor (poner en estado inactivo)
    public function deleteConductor($id) {
        $query = "UPDATE usuarios SET estado = 'inactivo' WHERE id = :id AND rol = 'conductor'";
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
