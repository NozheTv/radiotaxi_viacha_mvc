<?php
// Incluir header global
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';

// Cargar conexión a la base de datos
require_once APP_ROOT . '/config/database.php';
$database = new Database();
$pdo = $database->getConnection();

if ($pdo === null) {
    die("Error: No se pudo establecer conexión con la base de datos.");
}

// ✅ CONSULTAS CORREGIDAS con guiones bajos (_)
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalConductores = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'conductor' AND estado = 'activo'")->fetchColumn();
$viajesHoy = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE DATE(fecha_hora_solicitud) = CURDATE() AND id_estado_pedido = 4")->fetchColumn();
$totalGeocercas = $pdo->query("SELECT COUNT(*) FROM geocercas_tarifa")->fetchColumn();

$conductoresPendientes = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'conductor' AND (licencia IS NULL OR licencia = '') AND estado = 'activo'")->fetchColumn();
$viajesPendientes = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE id_estado_pedido IN (1,2)")->fetchColumn();

// Datos para gráficos - NOMBRES CORREGIDOS
$usuariosPorRol = $pdo->query("
    SELECT rol, COUNT(*) as total 
    FROM usuarios 
    WHERE estado = 'activo' 
    GROUP BY rol
")->fetchAll(PDO::FETCH_ASSOC);

$viajesPorEstado = $pdo->query("
    SELECT ep.descripcion, COUNT(p.id) as total
    FROM pedidos p
    JOIN estados_pedido ep ON p.id_estado_pedido = ep.id
    GROUP BY p.id_estado_pedido, ep.descripcion
")->fetchAll(PDO::FETCH_ASSOC);

$taxisPorEstado = $pdo->query("
    SELECT et.descripcion, COUNT(r.id) as total
    FROM radiotaxis r
    JOIN estados_taxi et ON r.id_estado_taxi = et.id
    GROUP BY r.id_estado_taxi, et.descripcion
")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="dashboard-main">
    <h2>Bienvenido, Administrador</h2>
    <section class="dashboard-sections">
        <div class="admin-widget">
            <h3>Resumen del Sistema</h3>
            <ul>
                <li>Total Usuarios: <strong><?php echo $totalUsuarios; ?></strong></li>
                <li>Conductores Activos: <strong><?php echo $totalConductores; ?></strong></li>
                <li>Viajes Hoy: <strong><?php echo $viajesHoy; ?></strong></li>
                <li>Geocercas: <strong><?php echo $totalGeocercas; ?></strong></li>
            </ul>
        </div>

        <div class="admin-widget">
            <h3>Alertas</h3>
            <ul>
                <li>⚠️ <strong><?php echo $conductoresPendientes; ?></strong> conductores sin licencia</li>
                <li>⏳ <strong><?php echo $viajesPendientes; ?></strong> viajes pendientes</li>
            </ul>
        </div>

        <!-- Canvas ÚNICOS con data-atributos -->
        <div class="admin-widget">
            <h3>Usuarios por rol</h3>
            <canvas id="usuariosChart" data-chart="usuarios"></canvas>
        </div>

        <div class="admin-widget">
            <h3>Viajes por estado</h3>
            <canvas id="viajesChart" data-chart="viajes"></canvas>
        </div>

        <div class="admin-widget">
            <h3>Estado de taxis</h3>
            <canvas id="taxisChart" data-chart="taxis"></canvas>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ✅ SOLUCIÓN DEFINITIVA: Chart único por canvas + verificación estricta
window.dashboardCharts = {};

function createChart(canvasId, chartType, data, labelsKey, valueKey, colors) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    // ✅ DESTROY si existe
    if (window.dashboardCharts[canvasId]) {
        window.dashboardCharts[canvasId].destroy();
        delete window.dashboardCharts[canvasId];
    }

    const ctx = canvas.getContext('2d');
    if (!ctx) {
        console.error('No se pudo obtener contexto 2D para:', canvasId);
        return;
    }

    window.dashboardCharts[canvasId] = new Chart(ctx, {
        type: chartType,
        data: {
            labels: data.map(item => item[labelsKey]),
            datasets: [{
                data: data.map(item => parseInt(item[valueKey]) || 0),
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Datos desde PHP
    const usuariosData = <?php echo json_encode($usuariosPorRol); ?>;
    const viajesData = <?php echo json_encode($viajesPorEstado); ?>;
    const taxisData = <?php echo json_encode($taxisPorEstado); ?>;

    // Crear charts UNA SOLA VEZ
    if (usuariosData.length > 0) {
        createChart('usuariosChart', 'doughnut', usuariosData, 'rol', 'total', ['#36A2EB', '#FF6384', '#FFCE56']);
    }
    
    if (viajesData.length > 0) {
        createChart('viajesChart', 'bar', viajesData, 'descripcion', 'total', ['#36A2EB']);
    }
    
    if (taxisData.length > 0) {
        createChart('taxisChart', 'pie', taxisData, 'descripcion', 'total', ['#4BC0C0', '#FF6384', '#FFCE56']);
    }
});
</script>

<style>
canvas { 
    max-height: 250px !important; 
    width: 100% !important; 
    border: 1px solid #eee;
}
.admin-widget { margin-bottom: 20px; }
</style>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
