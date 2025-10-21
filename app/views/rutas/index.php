<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/rutas.css" />

<main class="dashboard-main">
    <h2>Historial de Viajes Realizados</h2>
    <section>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Conductor</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historiales as $historial): ?>
                    <tr>
                        <td><?= htmlspecialchars($historial['created_at']) ?></td>
                        <td><?= htmlspecialchars($historial['cliente_nombre']) ?></td>
                        <td><?= htmlspecialchars($historial['conductor_nombre']) ?></td>
                        <td><a href="<?= BASE_URL . 'rutas/detalle/' . $historial['id'] ?>">Ver Detalle</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
