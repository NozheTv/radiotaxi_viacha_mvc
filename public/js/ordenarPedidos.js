function ordenarPedidosPorPrioridad(pedidos) {
    // Filtrar sólo los pedidos cuyo estado sea "pendiente" (id_estado_pedido === 1)
    const pedidosPendientes = pedidos.filter(pedido => pedido.id_estado_pedido === 1);

    // Ordenar los pedidos pendientes por prioridad descendente y fecha ascendente
    return pedidosPendientes.sort((a, b) => {
        if (a.prioridad > b.prioridad) return -1;
        if (a.prioridad < b.prioridad) return 1;

        const fechaA = new Date(a.fecha_hora_solicitud);
        const fechaB = new Date(b.fecha_hora_solicitud);

        if (fechaA < fechaB) return -1;
        if (fechaA > fechaB) return 1;

        return 0;
    });
}
