<?php
require_once APP_ROOT . '/models/Pedido.php';
require_once APP_ROOT . '/models/Usuario.php';
require_once APP_ROOT . '/controllers/AuthController.php';

class PedidoController {
    private $pedidoModel;
    private $usuarioModel;

    /**
     * Constructor inicializa conexiones y modelos
     */
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->pedidoModel = new Pedido($db);
        $this->usuarioModel = new Usuario($db);
    }

    /**
     * Método para mostrar lista de pedidos sin orden, la ordenación será en frontend
     */
    public function index() {
        AuthController::checkAuth();

        // Obtiene pedidos sin ordenar desde la base de datos
        $pedidos = $this->pedidoModel->getPedidos();
        require_once APP_ROOT . '/views/pedido/index.php';
    }

    /**
     * Mostrar detalle de un pedido específico
     *
     * @param int $id ID del pedido
     */
    public function show($id) {
        AuthController::checkAuth();

        // Obtener pedido por id
        $pedido = $this->pedidoModel->getPedidoById($id);
        // Obtener lista de conductores para select en vista
        $conductores = $this->usuarioModel->getConductores();

        require_once APP_ROOT . '/views/pedido/show.php';
    }

    /**
     * Mostrar formulario editar estado de pedido
     *
     * @param int $id ID del pedido
     */
    public function edit($id) {
        AuthController::checkAuth();

        // Obtener pedido para editar
        $pedido = $this->pedidoModel->getPedidoById($id);
        require_once APP_ROOT . '/views/pedido/edit.php';
    }

    /**
     * Crear nuevo pedido con datos recibidos
     *
     * @param array $data Datos para crear el pedido
     * @return array Resultado operación con éxito o error
     */
    public function crear($data) {
        $this->pedidoModel->id_cliente = $data['id_cliente'];
        $this->pedidoModel->origen_latitud = $data['origen_latitud'];
        $this->pedidoModel->origen_longitud = $data['origen_longitud'];
        $this->pedidoModel->destino_latitud = $data['destino_latitud'];
        $this->pedidoModel->destino_longitud = $data['destino_longitud'];
        $this->pedidoModel->tarifa = $data['tarifa'];
        $this->pedidoModel->id_estado_pedido = 1; // Estado pendiente por defecto
        $this->pedidoModel->prioridad = $data['prioridad'] ?? false;

        if ($this->pedidoModel->crearPedido()) {
            return ['success' => true, 'message' => 'Pedido creado correctamente'];
        }
        return ['success' => false, 'message' => 'Error al crear pedido'];
    }

    /**
     * Asignar taxi (conductor) a un pedido y actualizar estado a asignado
     *
     * @param int $id_pedido ID del pedido
     */
    public function aceptarPedido($id_pedido) {
        // Obtener id_taxi desde POST
        $id_taxi = $_POST['id_taxi'] ?? null;
        if ($id_taxi && $this->pedidoModel->asignarTaxi($id_pedido, $id_taxi)) {
            // Actualizar estado pedido a asignado (id_estado 2)
            $this->pedidoModel->actualizarEstado($id_pedido, 2);
            // Puedes agregar redirección o mensaje aquí
            return ['success' => true, 'message' => 'Pedido asignado al taxi'];
        }
        return ['success' => false, 'message' => 'Error al asignar taxi'];
    }

    /**
     * Actualizar estado de un pedido específico
     *
     * @param int $id_pedido ID del pedido
     */
    public function actualizarEstado($id_pedido) {
        // Obtener nuevo estado desde POST
        $id_estado = $_POST['estado'] ?? null;
        if ($id_estado && $this->pedidoModel->actualizarEstado($id_pedido, $id_estado)) {
            // Puedes agregar redirección o mensaje aquí
            return ['success' => true, 'message' => 'Estado actualizado'];
        }
        return ['success' => false, 'message' => 'Error al actualizar estado'];
    }

    /**
     * Método para obtener un solo pedido - uso interno o API
     */
    public function mostrar($id) {
        return $this->pedidoModel->getPedidoById($id);
    }

    /**
     * Obtener pedidos asociados a un cliente específico
     */
    public function pedidosDeCliente($id_cliente) {
        return $this->pedidoModel->getPedidosByCliente($id_cliente);
    }

    /**
     * Obtener pedidos con prioridad pendiente (uso interno o API)
     */
    public function pedidosPrioridad() {
        return $this->pedidoModel->getPedidosPendientesPrioridad();
    }
}
?>
