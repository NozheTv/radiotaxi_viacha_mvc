<?php
require_once APP_ROOT . '/views/partials/header.php';
require_once APP_ROOT . '/views/partials/sidebar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/clientes.css?v=<?php echo time(); ?>" />


<main class="dashboard-main">
    <h2>Crear Nuevo Cliente</h2>

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

    <form action="<?php echo BASE_URL; ?>clientes/store" method="post" id="clienteForm">
        <fieldset>
            <legend>Información de Cliente</legend>

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
                type="email" 
                id="email" 
                name="email" 
                required 
                placeholder="cliente@gmail.com" 
                minlength="4" 
                maxlength="40" 
                pattern="[a-zA-Z0-9._%+-]+@gmail\.com$"
                value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email'] ?? '') : ''; ?>" 
                title="El email debe ser de Gmail y terminar en .com"
            />

            <label for="telefono">Teléfono:</label>
            <input 
                type="tel" 
                id="telefono" 
                name="telefono" 
                placeholder="Opcional" 
                maxlength="15" 
                minlength="8"
                pattern="^\d{0,15}$" 
                inputmode="numeric" 
                title="Solo números, máximo 15 dígitos"
                value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : ''; ?>" 
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

            <button type="submit">Guardar Cliente</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>

<script>
// --- BLOQUEAR ESPACIOS EN EMAIL Y PASSWORD ---
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const nombreInput = document.getElementById('nombre');
const form = document.getElementById('clienteForm');

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