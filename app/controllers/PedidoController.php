<?php
require_once APP_ROOT . '/models/Pedido.php';

class PedidoController {
    private $pedidoModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->pedidoModel = new Pedido($db);
    }

    // Crear un pedido nuevo
    public function crear($data) {
        $this->pedidoModel->id_cliente = $data['id_cliente'];
        $this->pedidoModel->origen_latitud = $data['origen_latitud'];
        $this->pedidoModel->origen_longitud = $data['origen_longitud'];
        $this->pedidoModel->destino_latitud = $data['destino_latitud'];
        $this->pedidoModel->destino_longitud = $data['destino_longitud'];
        $this->pedidoModel->tarifa = $data['tarifa'];
        $this->pedidoModel->id_estado_pedido = 1; // Estado: pendiente
        $this->pedidoModel->prioridad = $data['prioridad'] ?? false;

        if ($this->pedidoModel->crearPedido()) {
            return ['success' => true, 'message' => 'Pedido creado correctamente'];
        }
        return ['success' => false, 'message' => 'Error al crear pedido'];
    }

    // Asignar taxi a pedido y actualizar estado
    public function aceptarPedido($id_pedido, $id_taxi) {
        if ($this->pedidoModel->asignarTaxi($id_pedido, $id_taxi)) {
            // Cambiar estado a asignado (ejemplo ID 2)
            $this->pedidoModel->actualizarEstado($id_pedido, 2);
            return ['success' => true, 'message' => 'Pedido asignado al taxi'];
        }
        return ['success' => false, 'message' => 'Error al asignar taxi'];
    }

    // Cambiar estado de pedido (en camino, finalizado)
    public function actualizarEstado($id_pedido, $id_estado) {
        if ($this->pedidoModel->actualizarEstado($id_pedido, $id_estado)) {
            return ['success' => true, 'message' => 'Estado actualizado'];
        }
        return ['success' => false, 'message' => 'Error al actualizar estado'];
    }

    public function mostrar($id) {
        return $this->pedidoModel->getPedidoById($id);
    }

    public function pedidosDeCliente($id_cliente) {
        return $this->pedidoModel->getPedidosByCliente($id_cliente);
    }

    public function pedidosPrioridad() {
        return $this->pedidoModel->getPedidosPendientesPrioridad();
    }
}
?>
