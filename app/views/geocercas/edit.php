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

<main class="dashboard-main">
    <h2>Editar Geocerca</h2>
    <form action="<?php echo BASE_URL . 'geocercas/update/' . $geocerca['id']; ?>" method="post" id="geocercaForm">
        <label for="nombre_zona">Nombre de la Zona</label>
        <input type="text" id="nombre_zona" name="nombre_zona" required value="<?= htmlspecialchars($geocerca['nombre_zona']) ?>" />

        <label for="tarifa_fija">Tarifa Fija</label>
        <input type="number" id="tarifa_fija" name="tarifa_fija" step="0.01" min="0" required value="<?= htmlspecialchars($geocerca['tarifa_fija']) ?>" />

        <label>Editar Polígono Geográfico</label>
        <div id="map" style="width: 100%; height: 400px; border-radius: 8px;"></div>

        <textarea id="poligono_geojson" name="poligono_geojson" hidden required><?= htmlspecialchars($geocerca['poligono_geojson']) ?></textarea>

        <button type="submit">Actualizar Geocerca</button>
    </form>
</main>

<script>
mapboxgl.accessToken = 'pk.eyJ1Ijoibm96aGUiLCJhIjoiY2x3Z2RjaDhrMDN0ZTJqcW1xdW5hbDcxMCJ9.9GLg27CrxP4E9xbOL-GiIg';

const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-68.15, -16.5],
    zoom: 10
});

// Inicia con arreglo vacio para que se dibuje nuevo polígono
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

    map.on('click', (e) => {
        coordinates.push([e.lngLat.lng, e.lngLat.lat]);
        geojson.features[0].geometry.coordinates = [coordinates.concat([coordinates[0]])];
        map.getSource('polygon').setData(geojson);
    });

    document.getElementById('geocercaForm').addEventListener('submit', (event) => {
        if (coordinates.length === 0) {
            alert('Debe definir un polígono en el mapa');
            event.preventDefault();
            return;
        }
        document.getElementById('poligono_geojson').value = JSON.stringify(geojson);
    });
});
</script>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
