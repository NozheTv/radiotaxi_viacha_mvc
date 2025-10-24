<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/conductores.css" />

<main class="dashboard-main">
    <h2>Editar Conductor</h2>

    <!-- Mostrar errores -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL . 'conductores/update/' . $conductor['id']; ?>" method="post" >
        <fieldset>
            <legend>Información de Conductor</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Nombre completo" maxlength="40"
                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+( [A-Za-zÁÉÍÓÚáéíóúÑñ]+){0,3}$"
                title="Solo letras y espacios intercalados (máximo 3 espacios). No se permiten números ni símbolos."
                value="<?php 
                    echo isset($_GET['nombre']) 
                        ? htmlspecialchars($_GET['nombre']) 
                        : htmlspecialchars($conductor['nombre']); 
                ?>" />

            <input type="email" id="email" name="email" required placeholder="correo@correo.com" maxlength="40"
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
            title="Debe ser un correo válido en minúsculas, por ejemplo: ejemplo@correo.com"
            value="<?php 
                echo isset($_GET['email']) 
                    ? htmlspecialchars($_GET['email']) 
                    : htmlspecialchars($conductor['email']); 
            ?>" />

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Opcional" maxlength="15" 
                pattern="\d{0,15}" inputmode="numeric" 
                title="Solo números, máximo 15 dígitos"
                value="<?php 
                    echo isset($_GET['telefono']) 
                        ? htmlspecialchars($_GET['telefono']) 
                        : htmlspecialchars($conductor['telefono']); 
                ?>" />

            <button type="submit">Actualizar Conductor</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
