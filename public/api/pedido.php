<?php
require_once dirname(__DIR__, 2) . '/app/config/database.php';
require_once dirname(__DIR__, 2) . '/app/models/Pedido.php';
require_once dirname(__DIR__, 2) . '/app/models/Geocerca.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$pedidoModel = new Pedido($db);
$geocercaModel = new Geocerca($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validación de campos básicos
if (
    !isset($data['id_cliente']) || !isset($data['origen_latitud']) || !isset($data['origen_longitud']) ||
    !isset($data['destino_latitud']) || !isset($data['destino_longitud']) || !isset($data['prioridad'])
) {
    http_response_code(400);
    echo json_encode(['message' => 'Faltan datos obligatorios']);
    exit;
}

// Función para verificar si un punto está en una geocerca (polígono geojson)
function puntoEnGeocerca($lat, $lon, $poligonoGeojson): bool {
    $geojson = json_decode($poligonoGeojson, true);
    if (!$geojson) return false;

    // Obtener el array de coordenadas para el polígono
    $coords = null;

    // Dependiendo del tipo GeoJSON:
    if (isset($geojson['type'])) {
        if ($geojson['type'] === 'FeatureCollection' && isset($geojson['features'][0]['geometry']['coordinates'])) {
            $coords = $geojson['features'][0]['geometry']['coordinates'][0];
        } elseif ($geojson['type'] === 'Feature' && isset($geojson['geometry']['coordinates'])) {
            $coords = $geojson['geometry']['coordinates'][0];
        } elseif ($geojson['type'] === 'Polygon' && isset($geojson['coordinates'])) {
            $coords = $geojson['coordinates'][0];
        }
    }

    if (!$coords || !is_array($coords)) {
        return false;
    }

    // Algoritmo de ray casting,

    $inside = false;
    $j = count($coords) - 1;

    for ($i = 0; $i < count($coords); $i++) {
        $xi = $coords[$i][1];
        $yi = $coords[$i][0];
        $xj = $coords[$j][1];
        $yj = $coords[$j][0];

        if ((($yi > $lat) != ($yj > $lat)) &&
            ($lon < ($xj - $xi) * ($lat - $yi) / ($yj - $yi) + $xi)) {
            $inside = !$inside;
        }
        $j = $i;
    }
    return $inside;
}


// Obtener todas las geocercas
$geocercas = $geocercaModel->getAll();

$tarifaBase = null;
foreach ($geocercas as $geocerca) {
    if (puntoEnGeocerca(floatval($data['origen_latitud']), floatval($data['origen_longitud']), $geocerca['poligono_geojson'])) {
        $tarifaBase = floatval($geocerca['tarifa_fija']);
        break;
    }
}

// Si no está en ninguna geocerca, tarifa mínima
if ($tarifaBase === null) {
    $tarifaBase = 6.00;
}

// Verificar si el destino está dentro de alguna geocerca
$destinoEnZona = false;
foreach ($geocercas as $geocerca) {
    if (puntoEnGeocerca(floatval($data['destino_latitud']), floatval($data['destino_longitud']), $geocerca['poligono_geojson'])) {
        $destinoEnZona = true;
        break;
    }
}

// Aplicar incremento si el destino está fuera de la geocerca
$tarifaFinal = $tarifaBase;
if (!$destinoEnZona) {
    $tarifaFinal *= 1.5; // incremento del 50%
}

// Preparar datos para crear el pedido
$pedidoModel->id_cliente = intval($data['id_cliente']);
$pedidoModel->origen_latitud = floatval($data['origen_latitud']);
$pedidoModel->origen_longitud = floatval($data['origen_longitud']);
$pedidoModel->destino_latitud = floatval($data['destino_latitud']);
$pedidoModel->destino_longitud = floatval($data['destino_longitud']);
$pedidoModel->tarifa = $tarifaFinal;
$pedidoModel->id_estado_pedido = 1; // estado pendiente
$pedidoModel->prioridad = boolval($data['prioridad']);

if ($pedidoModel->crearPedido()) {
    http_response_code(201);
    echo json_encode(['message' => 'Pedido creado correctamente', 'tarifa' => $tarifaFinal]);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error al crear pedido']);
}
