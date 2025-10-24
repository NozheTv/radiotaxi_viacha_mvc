<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/conductores.css" />

<main class="dashboard-main">
    <h2>Crear Nuevo Conductor</h2>

    <!-- Mostrar errores -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>

    <!-- Mostrar mensaje de éxito -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>conductores/store" method="post" >
        <fieldset>
            <legend>Información de Conductor</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Nombre completo" minlength="3" maxlength="40"
                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+( [A-Za-zÁÉÍÓÚáéíóúÑñ]+){0,3}$"
                title="Solo letras y espacios intercalados (máximo 3 espacios). No se permiten números ni símbolos."
                value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>" />

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="correo@correo.com" maxlength="40"
                value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" />

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Opcional" maxlength="15" 
                pattern="\d{0,15}" inputmode="numeric" 
                title="Solo números, máximo 15 dígitos"
                value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : ''; ?>" />

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required placeholder="Contraseña" 
                minlength="8" maxlength="15" pattern=".{8,15}" 
                title="La contraseña debe tener entre 8 y 15 caracteres" />

            <button type="submit">Guardar Conductor</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
