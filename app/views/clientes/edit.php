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

            <input type="text" id="nombre" name="nombre" required placeholder="Nombre completo" maxlength="40"
                   pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+( [A-Za-zÁÉÍÓÚáéíóúÑñ]+){0,3}$"
                   title="Solo letras y espacios intercalados (máximo 3 espacios). No se permiten números ni símbolos."
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
                value="<?php echo htmlspecialchars($cliente['email']); ?>" 
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
                value="<?php echo htmlspecialchars($cliente['telefono']); ?>" 
                oninput="validarTelefono(this)"
            />

            <button type="submit">Actualizar Cliente</button>
        </fieldset>
    </form>
</main>


<!-- JS para validar email completo -->
<script>
document.getElementById('form-cliente').addEventListener('submit', function(e) {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();

    // Regex simple para validar email completo con dominio
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!emailRegex.test(email)) {
        e.preventDefault(); // Detener envío
        alert('Por favor ingresa un correo electrónico válido completo (ej: ejemplo@gmail.com).');
        emailInput.focus();
    }
});
function validarEmail(input) {
    const valor = input.value.trim();
    // Explicación del regex:
    // ^ => inicio
    // [a-zA-Z0-9._%+-]{3,} => mínimo 3 caracteres antes de @
    // @ => símbolo obligatorio
    // [a-zA-Z0-9.-]{3,} => mínimo 3 caracteres en el dominio
    // \. => punto obligatorio
    // [a-zA-Z]{2,} => mínimo 2 caracteres en la extensión
    // $ => fin
    const regex = /^[a-zA-Z0-9._%+-]{3,}@[a-zA-Z0-9.-]{3,}\.[a-zA-Z]{3,}$/;

    if (!regex.test(valor)) {
        input.setCustomValidity('Correo no válido. Ejemplo: nombre@univalle.edu');
        input.reportValidity();
    } else {
        input.setCustomValidity('');
    }
}
function validarTelefono(input) {
    // Elimina caracteres no numéricos (por seguridad)
    input.value = input.value.replace(/\D/g, '');
    
    // Limita a 8 dígitos
    if (input.value.length > 8) {
        input.value = input.value.slice(0, 8);
    }
}
</script>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>
