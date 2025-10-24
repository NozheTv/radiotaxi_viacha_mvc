<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/radiotaxis.css" />

<main class="dashboard-main">
    <h2>Nuevo Radio Taxi</h2>

    <!-- Mostrar mensajes de error o éxito -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars(urldecode($_GET['error'])) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars(urldecode($_GET['success'])) ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>radiotaxis/store" method="post">
        <fieldset>
            <legend>Información del Taxi</legend>

            <label for="placa">Placa</label>
            <input 
                type="text" 
                id="placa" 
                name="placa" 
                required 
                placeholder="Placa única" 
                maxlength="8" 
                minlength="3"
                value="<?php echo isset($_GET['placa']) ? htmlspecialchars($_GET['placa']) : ''; ?>"
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
                value="<?php echo isset($_GET['modelo']) ? htmlspecialchars($_GET['modelo']) : ''; ?>"
            />

            <label for="id_conductor">Conductor</label>
            <select id="id_conductor" name="id_conductor">
                <option value="">Sin asignar</option>
                <?php foreach ($conductores as $conductor): ?>
                    <option 
                        value="<?= $conductor['id'] ?>" 
                        <?= (isset($_GET['id_conductor']) && $_GET['id_conductor'] == $conductor['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($conductor['nombre']) ?>
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
                value="<?php echo isset($_GET['gps_latitud']) ? htmlspecialchars($_GET['gps_latitud']) : ''; ?>"
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
                value="<?php echo isset($_GET['gps_longitud']) ? htmlspecialchars($_GET['gps_longitud']) : ''; ?>"
            />

            <button type="submit">Guardar</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
