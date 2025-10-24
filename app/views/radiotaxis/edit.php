<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/radiotaxis.css" />

<main class="dashboard-main">
    <h2>Editar Radio Taxi</h2>
    <form action="<?php echo BASE_URL . 'radiotaxis/update/' . $radiotaxi['id']; ?>" method="post" >
        <label for="placa">Placa</label>
            <input 
                type="text" 
                id="placa" 
                name="placa" 
                required 
                placeholder="Placa única" 
                maxlength="8" 
                minlength="3"
                value="<?= htmlspecialchars($radiotaxi['placa']) ?>"
            />

            <label for="modelo">Modelo</label>
            <input 
                type="text" 
                id="modelo" 
                name="modelo" 
                required 
                placeholder="Modelo del taxi" 
                maxlength="12" 
                minlength="5"
                value="<?= htmlspecialchars($radiotaxi['modelo']) ?>"
            />

            <label for="id_conductor">Conductor</label>
            <select id="id_conductor" name="id_conductor">
                <option value="">Sin asignar</option>
                <?php foreach ($conductores as $conductor): ?>
                    <option 
                        value="<?= $conductor['id'] ?>" 
                        <?= ($conductor['id'] == $radiotaxi['id_conductor']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($conductor['nombre_completo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="gps_latitud">GPS Latitud</label>
            <input 
                type="text" 
                id="gps_latitud" 
                name="gps_latitud"
                placeholder="Opcional"
                maxlength="20"
                inputmode="decimal"
                pattern="^-?\d{0,9}(\.\d{0,7})?$"
                title="Ejemplo: -16.5234567"
                value="<?= htmlspecialchars($radiotaxi['gps_latitud']) ?>"
            />

            <label for="gps_longitud">GPS Longitud</label>
            <input 
                type="text" 
                id="gps_longitud" 
                name="gps_longitud"
                placeholder="Opcional"
                maxlength="20"
                inputmode="decimal"
                pattern="^-?\d{0,9}(\.\d{0,7})?$"
                title="Ejemplo: -68.1234567"
                value="<?= htmlspecialchars($radiotaxi['gps_longitud']) ?>"
            />

        <button type="submit">Actualizar</button>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
