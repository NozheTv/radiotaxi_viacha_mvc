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
                <th>Origen (Lat, Long)</th>
                <th>Destino (Lat, Long)</th>
                <th>Tarifa</th>
                <th>Estado</th>
                <th>Conductor Asignado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?= htmlspecialchars($pedido['id']) ?></td>
                    <td><?= htmlspecialchars($pedido['nombre_cliente'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($pedido['origen_latitud']) ?>, <?= htmlspecialchars($pedido['origen_longitud']) ?></td>
                    <td><?= htmlspecialchars($pedido['destino_latitud']) ?>, <?= htmlspecialchars($pedido['destino_longitud']) ?></td>
                    <td><?= number_format($pedido['tarifa'], 2) ?> Bs.</td>
                    <td><?= htmlspecialchars($pedido['estado_nombre'] ?? 'Pendiente') ?></td>
                    <td><?= htmlspecialchars($pedido['nombre_conductor'] ?? 'Sin asignar') ?></td>
                    <td>
                        <a href="<?= BASE_URL . 'pedido/show/' . $pedido['id'] ?>">Ver</a> |
                        <a href="<?= BASE_URL . 'pedido/edit/' . $pedido['id'] ?>">Modificar Estado</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
