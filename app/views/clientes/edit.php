<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/clientes.css" />

<main class="dashboard-main">
    <h2>Editar Cliente</h2>
    <form action="<?php echo BASE_URL . 'clientes/update/' . $cliente['id']; ?>" method="post" >
        <fieldset>
            <legend>Información de Cliente</legend>

            <input type="text" id="nombre" name="nombre" required placeholder="Nombre completo" maxlength="40"
       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+( [A-Za-zÁÉÍÓÚáéíóúÑñ]+){0,3}$"
       title="Solo letras y espacios intercalados (máximo 3 espacios). No se permiten números ni símbolos."
       value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : htmlspecialchars($cliente['nombre']); ?>" />

<label for="email">Correo Electrónico:</label>
<input type="email" id="email" name="email" required placeholder="cliente@correo.com" maxlength="40"
       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : htmlspecialchars($cliente['email']); ?>" />

<label for="telefono">Teléfono:</label>
<input type="tel" id="telefono" name="telefono" placeholder="Opcional" maxlength="15" 
       pattern="\d{0,15}" inputmode="numeric" 
       title="Solo números, máximo 15 dígitos"
       value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : htmlspecialchars($cliente['telefono']); ?>" />
            <button type="submit">Actualizar Cliente</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
