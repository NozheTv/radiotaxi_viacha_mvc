<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>

<link href="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.js"></script>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/geocercas.css" />

<main class="dashboard-main">
    <h2>Nueva Geocerca</h2>
    <h3>
Cada clic sobre el mapa agrega un punto al polígono.
A medida que se hace clic, el sistema une los puntos y dibuja la forma en el mapa.
El área se guarda internamente como un objeto GeoJSON, que luego se convierte a texto JSON para almacenarse en la base de datos.</h3>
    <form action="<?php echo BASE_URL; ?>geocercas/store" method="post" id="geocercaForm">
        <label for="nombre_zona">Nombre de la Zona</label>
        <input type="text" id="nombre_zona" name="nombre_zona" required maxlength="20" minlength="4"
               placeholder="Ejemplo: Zona Norte" />
        <small id="nombreError" style="color: red; display: none;">⚠️ Este nombre ya existe, elija otro.</small>

        <label for="tarifa_fija">Tarifa Fija</label>
        <input type="number" id="tarifa_fija" name="tarifa_fija" step="0.01" min="7" max="999" required />

        <label>Definir Polígono Geográfico</label>
        <div id="map" style="width: 100%; height: 400px; border-radius: 8px;"></div>

        <textarea id="poligono_geojson" name="poligono_geojson" hidden></textarea>

        <button type="submit">Guardar Geocerca</button>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {

    mapboxgl.accessToken = 'pk.eyJ1Ijoibm96aGUiLCJhIjoiY2x3Z2RjaDhrMDN0ZTJqcW1xdW5hbDcxMCJ9.9GLg27CrxP4E9xbOL-GiIg';

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-68.15, -16.5],
        zoom: 10
    });

    let coordinates = [];
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

        map.getCanvas().style.cursor = 'crosshair';

        // Dibujar puntos del polígono
        map.on('click', (e) => {
            coordinates.push([e.lngLat.lng, e.lngLat.lat]);
            geojson.features[0].geometry.coordinates = [coordinates.concat([coordinates[0]])];
            map.getSource('polygon').setData(geojson);
        });

        const form = document.getElementById('geocercaForm');
        const nombreInput = document.getElementById('nombre_zona');
        const nombreError = document.getElementById('nombreError');

        // Evitar escribir espacios al inicio o final
        nombreInput.addEventListener('input', () => {
            // Elimina espacios al inicio automáticamente
            nombreInput.value = nombreInput.value.replace(/^\s+/, '');
        });

        // 🔍 Función para verificar si el nombre ya existe
        async function verificarNombre(nombre) {
            if (!nombre.trim()) return false;
            try {
                const response = await fetch("<?php echo BASE_URL; ?>geocercas/checkName?nombre=" + encodeURIComponent(nombre.trim()));
                const data = await response.json();
                return data.exists; // true o false
            } catch (error) {
                console.error("Error al verificar nombre:", error);
                return false;
            }
        }

        // 🧠 Evento de envío del formulario
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Evita envío inmediato

            const nombre = nombreInput.value.trim();

            if (nombre === '') {
                alert('⚠️ El nombre no puede estar vacío ni tener solo espacios.');
                nombreInput.focus();
                return;
            }

            const nombreExiste = await verificarNombre(nombre);

            if (nombreExiste) {
                nombreError.style.display = 'block';
                alert('❌ El nombre de la geocerca ya existe. Elija otro.');
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

            // ✅ Envía el formulario manualmente solo una vez
            form.submit();
        });
    });

});
</script>



<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
