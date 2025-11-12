<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/conductores.css?v=<?php echo time(); ?>" />

<main class="dashboard-main">
    <h2>Editar Conductor</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL . 'conductores/update/' . ($conductor['id'] ?? ''); ?>" method="post" id="conductorForm">
        <fieldset>
            <legend>Información de Conductor</legend>

            <label for="nombre">Nombre Completo:</label>
            <input 
                type="text" 
                id="nombre" 
                name="nombre" 
                required 
                placeholder="Nombre completo" 
                minlength="5" 
                maxlength="40"
                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}( [A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}){1,3}$"
                title="Debe contener al menos un nombre y un apellido, mínimo 2 letras cada uno. Máximo 4 palabras en total."
                value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : htmlspecialchars($conductor['nombre'] ?? ''); ?>" 
            />

            <label for="email">Correo Electrónico:</label>
            <input 
                type="text" 
                id="email" 
                name="email" 
                required 
                placeholder="ejemplo@gmail.com" 
                minlength="6" 
                maxlength="40" 
                title="Debe tener al menos 3 caracteres antes de @, 3 en el dominio y 2 o más en la extensión (ej: nombre@univalle.edu)"
                value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : htmlspecialchars($conductor['email'] ?? ''); ?>"
                oninput="validarEmail(this)"
            />

            <label for="telefono">Teléfono:</label>
            <input 
                type="number" 
                id="telefono" 
                name="telefono" 
                placeholder="Opcional"
                min="60000000" 
                max="99999999"
                inputmode="numeric" 
                title="Debe tener exactamente 8 dígitos y comenzar con 6, 7, 8 o 9"
                value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : htmlspecialchars($conductor['telefono'] ?? ''); ?>" 
                oninput="validarTelefono(this)"
            />

            <label for="direccion">Dirección:</label>
            <input 
                type="text" 
                id="direccion" 
                name="direccion" 
                placeholder="Número y calle" 
                maxlength="255"
                value="<?php echo isset($_GET['direccion']) ? htmlspecialchars($_GET['direccion']) : htmlspecialchars($conductor['direccion'] ?? ''); ?>"
            />

            <label for="licencia">Licencia:</label>
            <input 
                type="text" 
                id="licencia" 
                name="licencia" 
                required 
                placeholder="Número de licencia" 
                maxlength="100"
                value="<?php echo isset($_GET['licencia']) ? htmlspecialchars($_GET['licencia']) : htmlspecialchars($conductor['licencia'] ?? ''); ?>"
            />

            <!-- Campos ocultos para rol, estado y plataforma -->
            <input type="hidden" name="rol" value="conductor" />
            <input type="hidden" name="estado" value="<?php echo htmlspecialchars($conductor['estado'] ?? 'activo'); ?>" />
            <input type="hidden" name="plataforma_acceso" value="app_conductor" />

            <button type="submit">Actualizar Conductor</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>

<script src="<?php echo BASE_URL; ?>js/validaciones.js?v=<?php echo time(); ?>"></script>
<script>
  // Inicializar validaciones para formulario conductor editar
  setupValidacionesFormulario('conductorForm', 'nombre', 'email', '', 'telefono', 'direccion');
  // Nota: No validamos contraseña aquí porque no se edita en este formulario
</script>
