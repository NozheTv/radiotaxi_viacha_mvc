<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/radiotaxis.css" />

<main class="dashboard-main">
    <h2>Editar Radio Taxi</h2>
    <form action="<?php echo BASE_URL . 'radiotaxis/update/' . $radiotaxi['id']; ?>" method="post" novalidate>
        <label for="placa">Placa</label>
        <input type="text" id="placa" name="placa" required value="<?= htmlspecialchars($radiotaxi['placa']) ?>" />

        <label for="modelo">Modelo</label>
        <input type="text" id="modelo" name="modelo" required value="<?= htmlspecialchars($radiotaxi['modelo']) ?>" />

        <label for="id_conductor">Conductor</label>
        <select id="id_conductor" name="id_conductor" >
            <option value="">Sin asignar</option>
            <?php foreach ($conductores as $conductor): ?>
                <option value="<?= $conductor['id'] ?>" <?= ($conductor['id'] == $radiotaxi['id_conductor']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($conductor['nombre_completo']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="gps_latitud">GPS Latitud</label>
        <input type="number" step="0.0000001" id="gps_latitud" name="gps_latitud" placeholder="Opcional"
               value="<?= htmlspecialchars($radiotaxi['gps_latitud']) ?>" />

        <label for="gps_longitud">GPS Longitud</label>
        <input type="number" step="0.0000001" id="gps_longitud" name="gps_longitud" placeholder="Opcional"
               value="<?= htmlspecialchars($radiotaxi['gps_longitud']) ?>" />

        <button type="submit">Actualizar</button>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
