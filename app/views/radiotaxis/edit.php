<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/radiotaxis.css" />

<main class="dashboard-main">
    <h2>Editar Radio Taxi</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
        </div>
    <?php endif; ?>

    <form id="radiotaxiForm" action="<?php echo BASE_URL . 'radiotaxis/update/' . $radiotaxi['id']; ?>" method="post">
        <label for="placa">Placa</label>
        <input 
            type="text" 
            id="placa" 
            name="placa" 
            required 
            placeholder="Placa única (3 letras y 3-4 números)" 
            maxlength="8" 
            minlength="6"
            value="<?= htmlspecialchars($radiotaxi['placa'] ?? '') ?>"
        />

        <label for="modelo">Modelo</label>
        <input 
            type="text" 
            id="modelo" 
            name="modelo" 
            required 
            placeholder="Modelo del taxi (3 a 12 caracteres)" 
            maxlength="12" 
            minlength="3"
            value="<?= htmlspecialchars($radiotaxi['modelo'] ?? '') ?>"
        />

        <label for="id_conductor">Conductor</label>
        <select id="id_conductor" name="id_conductor">
            <option value="">Sin asignar</option>
            <?php foreach ($conductores as $conductor): ?>
                <option 
                    value="<?= $conductor['id'] ?>" 
                    <?php 
                        if (isset($_GET['id_conductor']) && $_GET['id_conductor'] == $conductor['id']) {
                            echo 'selected';
                        } elseif (isset($radiotaxi['id_conductor']) && $radiotaxi['id_conductor'] == $conductor['id']) {
                            echo 'selected';
                        }
                    ?>>
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
            value="<?= htmlspecialchars($radiotaxi['gps_latitud'] ?? '') ?>"
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
            value="<?= htmlspecialchars($radiotaxi['gps_longitud'] ?? '') ?>"
        />

        <button type="submit">Actualizar</button>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>

<script src="<?php echo BASE_URL; ?>js/validaciones.js?v=<?php echo time(); ?>"></script>
<script>
    // Inicializar validación para radiotaxis editar
    setupValidacionesRadiotaxis('radiotaxiForm', 'placa', 'modelo');
</script>
