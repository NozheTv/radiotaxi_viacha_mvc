<?php require_once APP_ROOT . '/views/partials/header.php'; ?>
<?php require_once APP_ROOT . '/views/partials/sidebar.php'; ?>

<!-- CSS de Mapbox -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

<style>
    #map {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        margin-bottom: 1.5rem;
    }
</style>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/pedidos.css" />



<main class="dashboard-main">
    <div class="header-container">
        <h2>Detalle Pedido #<?= htmlspecialchars($pedidos['id']) ?></h2>
        <a class="btn btn-primary" href="<?= BASE_URL ?>pedido">Volver a la lista de pedidos</a>
    </div>

    <div class="description-box">
        <strong>Cliente:</strong> <?= htmlspecialchars($pedidos['nombre_cliente'] ?? 'N/A') ?><br>
        <strong>Tarifa:</strong> <?= number_format($pedidos['tarifa'], 2) ?> Bs.<br>
        <strong>Estado Actual:</strong> <?= htmlspecialchars($pedidos['estado_nombre'] ?? 'Pendiente') ?><br>
        <strong>Conductor Asignado:</strong> <?= htmlspecialchars($pedidos['nombre_conductor'] ?? 'Sin asignar') ?><br>
        <strong>Fecha Solicitud:</strong> <?= htmlspecialchars($pedidos['fecha_hora_solicitud']) ?><br>
    </div>

    <hr>

    <h3>Ubicación (Origen y Destino)</h3>
    <div id="map"></div>

    <hr>

    <div class="forms-container">
    <div class="form-asignar">
        <h3>Asignar Conductor</h3>
        <form action="<?= BASE_URL ?>pedidos/asignarConductor/<?= $pedidos['id'] ?>" method="POST">
            <select name="id_conductor" required>
                <option value="">-- Seleccione un conductor --</option>
                <?php foreach ($conductores as $conductor): ?>
                    <option value="<?= $conductor['id'] ?>" <?= ($pedidos['id_taxi'] == $conductor['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($conductor['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Asignar</button>
        </form>
    </div>

    <div class="form-estado">
        <h3>Cambiar Estado</h3>
        <form action="<?= BASE_URL ?>pedidos/cambiarEstado/<?= $pedidos['id'] ?>" method="POST">
            <select name="estado" required>
                <option value="1" <?= ($pedidos['id_estado_pedido'] == 1) ? 'selected' : '' ?>>Pendiente</option>
                <option value="2" <?= ($pedidos['id_estado_pedido'] == 2) ? 'selected' : '' ?>>Asignado</option>
                <option value="3" <?= ($pedidos['id_estado_pedido'] == 3) ? 'selected' : '' ?>>En camino</option>
                <option value="4" <?= ($pedidos['id_estado_pedido'] == 4) ? 'selected' : '' ?>>Finalizado</option>
                <option value="5" <?= ($pedidos['id_estado_pedido'] == 5) ? 'selected' : '' ?>>Cancelado</option>
            </select>
            <button type="submit" class="btn btn-primary">Actualizar Estado</button>
        </form>
    </div>
</div>

<hr>


</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>

<!-- JS de Mapbox -->
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
<script>
    mapboxgl.accessToken = 'pk.eyJ1Ijoibm96aGUiLCJhIjoiY2x3Z2RjaDhrMDN0ZTJqcW1xdW5hbDcxMCJ9.9GLg27CrxP4E9xbOL-GiIg'; // Reemplaza con tu token Mapbox real
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [(<?= $pedidos['origen_longitud'] ?> + <?= $pedidos['destino_longitud'] ?>) / 2, (<?= $pedidos['origen_latitud'] ?> + <?= $pedidos['destino_latitud'] ?>) / 2],
        zoom: 12
    });

    // Crear popup para el origen con texto fijo
    const popupOrigen = new mapboxgl.Popup({ offset: 25 })
        .setHTML("<strong>Punto de partida</strong>");

    // Marcador Origen con popup
    new mapboxgl.Marker({color: 'green'})
        .setLngLat([<?= $pedidos['origen_longitud'] ?>, <?= $pedidos['origen_latitud'] ?>])
        .setPopup(popupOrigen)
        .addTo(map);

    // Crear popup para el destino
    const popupDestino = new mapboxgl.Popup({ offset: 25 })
        .setHTML("<strong>Destino</strong>");

    // Marcador Destino con popup
    new mapboxgl.Marker({color: 'red'})
        .setLngLat([<?= $pedidos['destino_longitud'] ?>, <?= $pedidos['destino_latitud'] ?>])
        .setPopup(popupDestino)
        .addTo(map);

    // Ajustar mapa para mostrar ambos puntos
    const bounds = new mapboxgl.LngLatBounds();
    bounds.extend([<?= $pedidos['origen_longitud'] ?>, <?= $pedidos['origen_latitud'] ?>]);
    bounds.extend([<?= $pedidos['destino_longitud'] ?>, <?= $pedidos['destino_latitud'] ?>]);
    map.fitBounds(bounds, { padding: 60, maxZoom: 15 });
</script>
