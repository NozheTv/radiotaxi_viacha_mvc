<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/clientes.css?v=<?php echo time(); ?>" />

<main class="dashboard-main">
    <h2>Editar Cliente</h2>

    <!-- Mensajes de éxito o error -->
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

    <form id="form-cliente" action="<?php echo BASE_URL . 'clientes/update/' . $cliente['id']; ?>" method="post">
        <fieldset>
            <legend>Información de Cliente</legend>

            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Nombre completo" maxlength="40"
                   pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}( [A-Za-zÁÉÍÓÚáéíóúÑñ]{2,}){1,3}$"
                   title="Debe contener al menos un nombre y un apellido, mínimo 2 letras cada uno. Máximo 4 palabras en total."
                   value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : htmlspecialchars($cliente['nombre']); ?>" />

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
                value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : htmlspecialchars($cliente['email']); ?>"
            />

            <label for="telefono">Teléfono:</label>
            <input 
                type="number" 
                id="telefono" 
                name="telefono" 
                required 
                placeholder="Teléfono (8 dígitos, comienza con 6-9)"
                min="60000000" 
                max="79999999"
                inputmode="numeric" 
                title="Debe tener exactamente 8 dígitos y comenzar con 6 o 7"
                value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : htmlspecialchars($cliente['telefono']); ?>" 
            />

            <label for="direccion">Dirección:</label>
            <input 
                type="text" 
                id="direccion" 
                name="direccion" 
                placeholder="Número y calle" 
                maxlength="255"
                value="<?php echo htmlspecialchars($_GET['direccion'] ?? $cliente['direccion'] ?? ''); ?>"
            />

            <!-- Campos ocultos para rol, estado y plataforma -->
            <input type="hidden" name="rol" value="cliente" />
            <input type="hidden" name="estado" value="<?php echo htmlspecialchars($cliente['estado'] ?? 'activo'); ?>" />
            <input type="hidden" name="plataforma_acceso" value="app_cliente" />

            <button type="submit">Actualizar Cliente</button>
        </fieldset>
    </form>
</main>

<script src="<?php echo BASE_URL; ?>js/validaciones.js?v=<?php echo time(); ?>"></script>
<script>
  // Inicializar validaciones para el formulario de edición clientes
  setupValidacionesFormulario('form-cliente', 'nombre', 'email', '', 'telefono', 'direccion');
  
  // Nota: No validamos contraseña aquí porque no se está editando desde este formulario
</script>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
