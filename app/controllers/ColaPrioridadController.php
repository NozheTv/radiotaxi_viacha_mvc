<?php
require_once APP_ROOT . '/models/ColaPrioridad.php';

class ColaPrioridadController {
    private $colaPrioridadModel;

    public function __construct($db) {
        $this->colaPrioridadModel = new ColaPrioridad($db);
    }

    // Añadir pedido a cola de prioridad si no está aún
    public function agregarPedido($id_pedido) {
        if (!$this->colaPrioridadModel->estaEnCola($id_pedido)) {
            return $this->colaPrioridadModel->insertarEnCola($id_pedido);
        }
        return false;
    }

    // Obtener lista de pedidos que están en cola de prioridad
    public function listarPedidosEnCola() {
        return $this->colaPrioridadModel->obtenerPedidosEnCola();
    }

    // Eliminar pedido de cola cuando se procese o asigne taxi
    public function quitarPedidoDeCola($id_pedido) {
        return $this->colaPrioridadModel->eliminarDeCola($id_pedido);
    }
}
?>
