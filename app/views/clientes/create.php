<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/clientes.css" />

<main class="dashboard-main">
    <h2>Crear Nuevo Cliente</h2>
    <form action="<?php echo BASE_URL; ?>clientes/store" method="post" novalidate>
        <fieldset>
            <legend>Información de Cliente</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Nombre completo" maxlength="40"/>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="cliente@correo.com" maxlength="40"/>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required placeholder="Opcional" maxlength="15" pattern="[0-9]{0,15}" inputmode="numeric"/>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password"  required placeholder="Contraseña" maxlength="30"/>

            <button type="submit">Guardar Cliente</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
