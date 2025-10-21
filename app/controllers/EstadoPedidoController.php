<?php
require_once APP_ROOT . '/models/EstadoPedido.php';

class EstadoPedidoController {
    private $estadoPedidoModel;

    public function __construct($db) {
        $this->estadoPedidoModel = new EstadoPedido($db);
    }

    public function listar() {
        return $this->estadoPedidoModel->listarEstados();
    }

    public function mostrar($id) {
        return $this->estadoPedidoModel->getEstadoById($id);
    }
}
?>
