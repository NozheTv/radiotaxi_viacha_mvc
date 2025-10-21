<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/conductores.css" />

<main class="dashboard-main">
    <h2>Editar Conductor</h2>
    <form action="<?php echo BASE_URL . 'conductores/update/' . $conductor['id']; ?>" method="post" novalidate>
        <fieldset>
            <legend>Información de Conductor</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($conductor['nombre']); ?>" />

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($conductor['email']); ?>" />

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($conductor['telefono']); ?>" />

            <button type="submit">Actualizar Conductor</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
