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
            <li>Teléfono: <?= htmlspecialchars($detalle['cliente_telefono'] ?? '') ?></li>
            <li>Teléfono: <?= htmlspecialchars($detalle['conductor_telefono'] ?? '') ?></li>

        </ul>

        <h3>Detalles de la Ruta</h3>
            <?php if (!empty($detalles)): ?>
                <ul>
                    <li><strong>Distancia recorrida:</strong> <?= htmlspecialchars($detalles['distancia_km'] ?? 'N/A') ?> kilómetros</li>
                    <li><strong>Tiempo estimado del viaje:</strong> <?= htmlspecialchars($detalles['tiempo_min'] ?? 'N/A') ?> minutos</li>
                    <li><strong>Ruta seguida:</strong> <?= htmlspecialchars($detalles['ruta_tomada'] ?? 'N/A') ?></li>
                </ul>
            <?php else: ?>
                <p>No hay detalles específicos para esta ruta.</p>
            <?php endif; ?>
    </section>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
