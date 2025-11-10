<?php require_once APP_ROOT . '/views/partials/header.php'; ?>
<?php require_once APP_ROOT . '/views/partials/sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/pedidos.css" />

<main class="dashboard-main">
    <h2>Pedidos Registrados</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Prioridad</th>
                <th>Tarifa</th>
                <th>Estado</th>
                <th>Conductor Asignado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="lista-pedidos">
            <!-- Aquí JavaScript insertará las filas ordenadas -->
        </tbody>
    </table>
</main>

<script>
    // Datos sin ordenar desde PHP convertidos a JSON para JS
    const pedidos = <?php echo json_encode($pedidos); ?>;
</script>

<script src="<?php echo BASE_URL; ?>js/ordenarPedidos.js"></script>

<script>
    // Función para renderizar los pedidos ordenados en la tabla
    function mostrarPedidos(pedidosOrdenados) {
        const contenedor = document.getElementById('lista-pedidos');
        contenedor.innerHTML = ''; // Limpiar contenido previo

        pedidosOrdenados.forEach(pedido => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${pedido.id}</td>
                <td>${pedido.nombre_cliente ?? 'N/A'}</td>
                <td>${pedido.prioridad == 1 ? 'Alta' : 'Normal'}</td>
                <td>${Number(pedido.tarifa).toFixed(2)} Bs.</td>
                <td>${pedido.estado_nombre ?? 'Pendiente'}</td>
                <td>${pedido.nombre_conductor ?? 'Sin asignar'}</td>
                <td>
                    <a class="btn-ver" href="<?= BASE_URL . 'pedido/show/' ?>${pedido.id}">Ver</a> |
                    <a class="btn-editar" href="<?= BASE_URL . 'pedido/edit/' ?>${pedido.id}">Modificar Estado</a>
                </td>
            `;

            contenedor.appendChild(tr);
        });
    }

    // Ordenar los pedidos por prioridad y fecha usando función importada
    const pedidosOrdenados = ordenarPedidosPorPrioridad(pedidos);
    mostrarPedidos(pedidosOrdenados);
</script>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
