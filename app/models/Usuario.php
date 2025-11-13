    <?php
    class Usuario {
        private $conn;
        private $table = "usuarios";

        public $id;
        public $nombre;
        public $email;
        public $password;
        public $rol; // admin, conductor, cliente
        public $estado; // activo, inactivo

        public function __construct($db) {
            $this->conn = $db;
        }

        // ------------------------------
        // LOGIN
        // ------------------------------
        public function login($email) {
            $query = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // retorna array o false
        }

        // ------------------------------
        // CLIENTES
        // ------------------------------
        public function getClientes() {
            $query = "SELECT * FROM " . $this->table . " WHERE rol = 'cliente' AND estado = 'activo' ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getClienteById($id) {
            return $this->getByIdAndRol($id, 'cliente');
        }

        public function createCliente($data) {
            return $this->createUsuario($data, 'cliente');
        }

        public function updateCliente($id, $data) {
            return $this->updateUsuario($id, $data, 'cliente');
        }

        public function deleteCliente($id) {
            return $this->deleteUsuario($id, 'cliente');
        }

        // ------------------------------
        // CONDUCTORES
        // ------------------------------
        public function getConductores() {
            $query = "SELECT * FROM " . $this->table . " WHERE rol = 'conductor' AND estado = 'activo' ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getConductorById($id) {
            return $this->getByIdAndRol($id, 'conductor');
        }

        public function createConductor($data) {
            return $this->createUsuario($data, 'conductor');
        }

        public function updateConductor($id, $data) {
            return $this->updateUsuario($id, $data, 'conductor');
        }

        public function deleteConductor($id) {
            return $this->deleteUsuario($id, 'conductor');
        }

        // ------------------------------
        // GENERALES
        // ------------------------------
        public function getByEmail($email) {
            $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create() {
            if (empty($this->rol)) {
                $this->rol = 'cliente';
            }
            return $this->createUsuario([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'password' => $this->password,
                'telefono' => null
            ], $this->rol);
        }

        public function readAll() {
            $query = "SELECT id, nombre, email, rol, estado FROM " . $this->table . " WHERE estado = 'activo'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function readOne() {
            $query = "SELECT id, nombre, email, rol, estado FROM " . $this->table . " WHERE id = ? AND estado = 'activo' LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $this->nombre = $row['nombre'];
                $this->email = $row['email'];
                $this->rol = $row['rol'];
                $this->estado = $row['estado'];
                return true;
            }
            return false;
        }

        public function update() {
            return $this->updateUsuario($this->id, [
                'nombre' => $this->nombre,
                'email' => $this->email,
                'password' => $this->password,
                'telefono' => null
            ], $this->rol);
        }

        public function delete() {
            return $this->deleteUsuario($this->id);
        }

        // ------------------------------
        // FUNCIONES PRIVADAS GENERALES
        // ------------------------------
        private function getByIdAndRol($id, $rol) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND rol = :rol";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':rol', $rol);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        private function createUsuario($data, $rol) {
            // Verificar email duplicado
            if ($this->getByEmail($data['email'])) {
                throw new Exception("El correo ya está registrado. Por favor usa otro correo electrónico.");
            }

            $query = "INSERT INTO " . $this->table . " (nombre, email, telefono, rol, password, estado)
                    VALUES (:nombre, :email, :telefono, :rol, :password, 'activo')";
            $stmt = $this->conn->prepare($query);

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':password', $hashedPassword);

            return $stmt->execute();
        }

        public function createUsuarioComplete($data) {
            // Verificar email duplicado
            if ($this->getByEmail($data['email'])) {
                throw new Exception("El correo ya está registrado. Por favor usa otro correo electrónico.");
            }

            $query = "INSERT INTO " . $this->table . " 
                (nombre, email, telefono, direccion, plataforma_acceso, rol, password, estado) 
                VALUES (:nombre, :email, :telefono, :direccion, :plataforma_acceso, :rol, :password, :estado)";
            $stmt = $this->conn->prepare($query);

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':direccion', $data['direccion']);
            $stmt->bindParam(':plataforma_acceso', $data['plataforma_acceso']);
            $stmt->bindParam(':rol', $data['rol']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':estado', $data['estado']);

            return $stmt->execute();
        }


        private function updateUsuario($id, $data, $rol = null) {
        // Validar que el email no esté registrado en otro usuario diferente
        $queryCheck = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email AND id != :id";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':email', $data['email']);
        $stmtCheck->bindParam(':id', $id);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            throw new Exception("El correo ya está registrado en otro usuario.");
        }

        // Construir query de actualización
        $query = "UPDATE " . $this->table . " SET nombre=:nombre, email=:email, telefono=:telefono";
        if (!empty($data['password'])) {
            $query .= ", password=:password";
        }
        if ($rol) {
            $query .= ", rol=:rol";
        }
        $query .= " WHERE id=:id";
        if ($rol) {
            $query .= " AND rol=:rol";
        }

        $stmt = $this->conn->prepare($query);

        // Bind de parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':id', $id);

        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
        }
        if ($rol) {
            $stmt->bindParam(':rol', $rol);
        }

        return $stmt->execute();
    }



        private function deleteUsuario($id, $rol = null) {
            $query = "UPDATE " . $this->table . " SET estado='inactivo' WHERE id=:id";
            if ($rol) {
                $query .= " AND rol=:rol";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            if ($rol) {
                $stmt->bindParam(':rol', $rol);
            }

            return $stmt->execute();
        }
    }
