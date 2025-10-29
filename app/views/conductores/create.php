<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/conductores.css?v=<?php echo time(); ?>" />

<main class="dashboard-main">
    <h2>Crear Nuevo Conductor</h2>

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

    <form action="<?php echo BASE_URL; ?>conductores/store" method="post" id="conductorForm">
        <fieldset>
            <legend>Información de Conductor</legend>

            <label for="nombre">Nombre Completo:</label>
            <input 
                type="text" 
                id="nombre" 
                name="nombre" 
                required 
                placeholder="Nombre completo" 
                minlength="3" 
                maxlength="40"
                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+( [A-Za-zÁÉÍÓÚáéíóúÑñ]+){0,3}$"
                title="Solo letras y espacios intercalados (máximo 3 espacios). No se permiten números ni símbolos."
                value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>" 
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
                value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email'] ?? '') : ''; ?>" 
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
                value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : ''; ?>" 
                oninput="validarTelefono(this)"
            />

            <label for="password">Contraseña:</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="Contraseña" 
                minlength="8" 
                maxlength="15" 
                pattern=".{8,15}" 
                title="La contraseña debe tener entre 8 y 15 caracteres"
            />

            <button type="submit">Guardar Conductor</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>

<script>
// --- BLOQUEAR ESPACIOS EN EMAIL Y PASSWORD ---
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const nombreInput = document.getElementById('nombre');
const form = document.getElementById('conductorForm');
function validarTelefono(input) {
    // Elimina caracteres no numéricos (por seguridad)
    input.value = input.value.replace(/\D/g, '');
    
    // Limita a 8 dígitos
    if (input.value.length > 8) {
        input.value = input.value.slice(0, 8);
    }
}
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
    const regex = /^[a-zA-Z0-9._%+-]{3,}@[a-zA-Z0-9.-]{3,}\.[a-zA-Z]{2,}$/;

    if (!regex.test(valor)) {
        input.setCustomValidity('Correo no válido. Ejemplo: nombre@univalle.edu');
        input.reportValidity();
    } else {
        input.setCustomValidity('');
    }
}
// Evita escribir espacios en correo y contraseña
[emailInput, passwordInput].forEach(input => {
    input.addEventListener('keydown', e => {
        if (e.key === ' ') e.preventDefault();
    });
    // También evita pegar texto con espacios
    input.addEventListener('paste', e => {
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        if (paste.includes(' ')) e.preventDefault();
    });
});

// Al enviar, eliminamos espacios al inicio y final de todos los campos
form.addEventListener('submit', e => {
    nombreInput.value = nombreInput.value.trim();
    emailInput.value = emailInput.value.trim();
    passwordInput.value = passwordInput.value.trim();

    // Si email o password contienen espacios intermedios, se bloquea el envío
    if (emailInput.value.includes(' ') || passwordInput.value.includes(' ')) {
        alert('El correo y la contraseña no pueden contener espacios.');
        e.preventDefault();
    }
});
</script>
