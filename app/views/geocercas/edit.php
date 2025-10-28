<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';

$geojson = json_decode($geocerca['poligono_geojson'], true);
$coordinates = [];
if (!empty($geojson) && isset($geojson['features'][0]['geometry']['coordinates'][0])) {
    $coordinates = $geojson['features'][0]['geometry']['coordinates'][0];
}
?>

<link href="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.js"></script>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/geocercas.css" />
<div class="content-wrapper">
    <main class="dashboard-main">
        <h2>Editar Geocerca</h2>
        <form action="<?php echo BASE_URL . 'geocercas/update/' . $geocerca['id']; ?>" method="post" id="geocercaForm">
            <label for="nombre_zona">Nombre de la Zona</label>
            <input type="text" id="nombre_zona" name="nombre_zona" required maxlength="20" minlength="4"
                value="<?= htmlspecialchars($geocerca['nombre_zona']) ?>" />
            <small id="nombreError" style="color: red; display: none;">⚠️ Este nombre ya existe, elija otro.</small>

            <label for="tarifa_fija">Tarifa Fija</label>
            <input type="number" id="tarifa_fija" name="tarifa_fija" step="0.01" min="6" max="50" required
                value="<?= htmlspecialchars($geocerca['tarifa_fija']) ?>" />

            <label>Editar Polígono Geográfico</label>
            <div id="map" style="width: 100%; height: 400px; border-radius: 8px;"></div>

            <textarea id="poligono_geojson" name="poligono_geojson" hidden required><?= htmlspecialchars($geocerca['poligono_geojson']) ?></textarea>

            <button type="submit">Actualizar Geocerca</button>
            <button type="button" id="btnLimpiar" style="margin-left: 10px;">Limpiar Geocerca</button>
        </form>
    </main>
</div>

<script>
mapboxgl.accessToken = 'pk.eyJ1Ijoibm96aGUiLCJhIjoiY2x3Z2RjaDhrMDN0ZTJqcW1xdW5hbDcxMCJ9.9GLg27CrxP4E9xbOL-GiIg';

const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-68.15, -16.5],
    zoom: 10
});

let coordinates = <?= json_encode($coordinates) ?>;

let geojson = {
    "type": "FeatureCollection",
    "features": [{
        "type": "Feature",
        "geometry": {
            "type": "Polygon",
            "coordinates": [coordinates]
        }
    }]
};

map.on('load', () => {
    map.addSource('polygon', { type: 'geojson', data: geojson });
    map.addLayer({
        id: 'polygon',
        type: 'fill',
        source: 'polygon',
        layout: {},
        paint: {
            'fill-color': '#088',
            'fill-opacity': 0.5
        }
    });

    // Centrar el mapa en el polígono
    if (coordinates.length > 0) {
        const bounds = coordinates.reduce((bounds, coord) => bounds.extend(coord),
            new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
        map.fitBounds(bounds, { padding: 20 });
    }

    map.getCanvas().style.cursor = 'crosshair';

    // Permitir agregar más puntos
    map.on('click', (e) => {
        coordinates.push([e.lngLat.lng, e.lngLat.lat]);
        geojson.features[0].geometry.coordinates = [coordinates.concat([coordinates[0]])];
        map.getSource('polygon').setData(geojson);
    });

    const form = document.getElementById('geocercaForm');
    const nombreInput = document.getElementById('nombre_zona');
    const nombreError = document.getElementById('nombreError');
    const idActual = <?= (int)$geocerca['id'] ?>;

    // Eliminar espacios al inicio automáticamente
    nombreInput.addEventListener('input', () => {
        nombreInput.value = nombreInput.value.replace(/^\s+/, '');
    });

    // 🔍 Verifica si el nombre ya existe (excepto el mismo ID)
    async function verificarNombre(nombre) {
        if (!nombre.trim()) return false;
        try {
            const response = await fetch("<?php echo BASE_URL; ?>geocercas/checkName?nombre=" + encodeURIComponent(nombre.trim()) + "&id=" + idActual);
            const data = await response.json();
            return data.exists;
        } catch (error) {
            console.error("Error al verificar nombre:", error);
            return false;
        }
    }

    // 🧠 Evento de envío del formulario
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const nombre = nombreInput.value.trim();

        if (nombre === '') {
            alert('⚠️ El nombre no puede estar vacío ni tener solo espacios.');
            nombreInput.focus();
            return;
        }

        const nombreExiste = await verificarNombre(nombre);

        if (nombreExiste) {
            nombreError.style.display = 'block';
            alert('❌ Ya existe otra geocerca con ese nombre. Elija otro.');
            nombreInput.focus();
            return;
        } else {
            nombreError.style.display = 'none';
        }

        if (coordinates.length < 3) {
            alert('⚠️ Debe definir un polígono con al menos 3 puntos.');
            return;
        }

        document.getElementById('poligono_geojson').value = JSON.stringify(geojson);

        // ✅ Envío definitivo del formulario solo una vez
        form.submit();
    });
});

const btnLimpiar = document.getElementById('btnLimpiar');

btnLimpiar.addEventListener('click', () => {
    // Vaciar coordenadas
    coordinates = [];

    // Actualizar geojson vacío
    geojson.features[0].geometry.coordinates = [[]];

    // Actualizar fuente y capa en el mapa para eliminar polígono
    map.getSource('polygon').setData(geojson);

    // Limpiar campo oculto del formulario
    document.getElementById('poligono_geojson').value = '';

    // Opcional: reubicar mapa a posición inicial y nivel de zoom
    map.flyTo({ center: [-68.15, -16.5], zoom: 10 });
});


</script>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
