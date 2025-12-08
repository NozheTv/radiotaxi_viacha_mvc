
<?php
// Incluir header global
require_once APP_ROOT . '/views/partials/header.php';

// Incluir barra lateral fija
require_once APP_ROOT . '/views/partials/sidebar.php';
?>

<main class="dashboard-main">
    <h2>Bienvenido, Administrador</h2>
        <section class="dashboard-sections">

        <!-- Widgets existentes -->
        <div class="admin-widget">
            <h3>Resumen del Sistema</h3>
            <ul>
                <li>Total de Usuarios Registrados: <strong>152</strong></li>
                <li>Conductores Activos: <strong>47</strong></li>
                <li>Viajes Completados Hoy: <strong>89</strong></li>
                <li>Geocercas Configuradas: <strong>12</strong></li>
            </ul>
        </div>

        <div class="admin-widget">
            <h3>Alertas del Sistema</h3>
            <ul>
                <li>⚠️ Hay <strong>3 conductores</strong> con documentos por verificar.</li>
                <li>⏳ 5 viajes en estado pendiente requieren revisión manual.</li>
                <li>📍 2 geocercas no tienen tarifa asignada.</li>
            </ul>
        </div>

        <div class="admin-widget">
            <h3>Tareas Recomendadas</h3>
            <ul>
                <li>✔️ Revisar las evaluaciones de los viajes del día.</li>
                <li>✔️ Confirmar asignación de vehículos a nuevos conductores.</li>
                <li>✔️ Verificar que todas las zonas tengan tarifas válidas.</li>
            </ul>
        </div>

        <!-- Nuevos gráficos -->
        <div class="admin-widget">
            <h3>Usuarios registrados por día</h3>
            <canvas id="usuariosChart" width="400" height="200"></canvas>
        </div>

        <div class="admin-widget">
            <h3>Viajes por estado</h3>
            <canvas id="viajesChart" width="400" height="200"></canvas>
        </div>

        <div class="admin-widget">
            <h3>Tipos de geocercas configuradas</h3>
            <canvas id="geocercasChart" width="400" height="200"></canvas>
        </div>

    </section>
</main>

<?php
// Incluir footer global
require_once APP_ROOT . '/views/partials/footer.php';
?>
