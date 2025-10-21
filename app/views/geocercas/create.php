<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>

<link href="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.js"></script>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/geocercas.css" />
<style>
#poligono_geojson {
    position: absolute;
    left: -9999px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}
</style>

<main class="dashboard-main">
    <h2>Nueva Geocerca</h2>
    <form action="<?php echo BASE_URL; ?>geocercas/store" method="post" id="geocercaForm">
        <label for="nombre_zona">Nombre de la Zona</label>
        <input type="text" id="nombre_zona" name="nombre_zona" required />

        <label for="tarifa_fija">Tarifa Fija</label>
        <input type="number" id="tarifa_fija" name="tarifa_fija" step="0.01" min="0" required />

        <label>Definir Polígono Geográfico</label>
        <div id="map" style="width: 100%; height: 400px; border-radius: 8px;"></div>

        <textarea id="poligono_geojson" name="poligono_geojson" style="display:none;"></textarea>

        <button type="submit">Guardar Geocerca</button>
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

let geojson = {
    "type": "FeatureCollection",
    "features": []
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

    let drawing = false;
    let coordinates = [];

    map.on('click', (e) => {
        if (!drawing) {
            drawing = true;
            coordinates = [];
        }
        
        coordinates.push([e.lngLat.lng, e.lngLat.lat]);

        if(coordinates.length > 2) {
            geojson.features = [{
                "type": "Feature",
                "geometry": {
                    "type": "Polygon",
                    "coordinates": [coordinates.concat([coordinates[0]])]
                }
            }];
            map.getSource('polygon').setData(geojson);
        }
    });

    map.getCanvas().style.cursor = 'crosshair';

    document.getElementById('geocercaForm').addEventListener('submit', (event) => {
        if (coordinates.length === 0) {
            alert('Debe definir un polígono en el mapa');
            event.preventDefault();
            return;
        }
        const poligonoGeojson = JSON.stringify(geojson);
        if (!poligonoGeojson) {
            alert('Polígono inválido, por favor defina la geocerca.');
            event.preventDefault();
            return;
        }
        document.getElementById('poligono_geojson').value = poligonoGeojson;
    });

});
</script>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
