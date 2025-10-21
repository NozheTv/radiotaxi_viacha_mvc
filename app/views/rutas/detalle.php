<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';

$detalles = json_decode($detalle['detalles_ruta'], true) ?? [];
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/rutas.css" />

<main class="dashboard-main">
    <h2>Detalle del Viaje ID: <?= htmlspecialchars($detalle['id']) ?></h2>
    <section>
        <h3>Cliente</h3>
        <ul>
            <li>Nombre: <?= htmlspecialchars($detalle['cliente_nombre']) ?></li>
            <li>Email: <?= htmlspecialchars($detalle['cliente_email']) ?></li>
            <li>Teléfono: <?= htmlspecialchars($detalle['cliente_telefono']) ?></li>
        </ul>

        <h3>Conductor</h3>
        <ul>
            <li>Nombre: <?= htmlspecialchars($detalle['conductor_nombre']) ?></li>
            <li>Email: <?= htmlspecialchars($detalle['conductor_email']) ?></li>
            <li>Teléfono: <?= htmlspecialchars($detalle['conductor_telefono']) ?></li>
        </ul>

        <h3>Detalles de la Ruta</h3>
        <?php if (!empty($detalles)): ?>
            <pre><?= json_encode($detalles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
        <?php else: ?>
            <p>No hay detalles específicos para esta ruta.</p>
        <?php endif; ?>

        <h3>Evaluación</h3>
        <ul>
            <li>Evaluación Cliente: <?= htmlspecialchars($detalle['evaluacion_cliente'] ?? 'N/A') ?></li>
            <li>Evaluación Conductor: <?= htmlspecialchars($detalle['evaluacion_conductor'] ?? 'N/A') ?></li>
        </ul>
    </section>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
