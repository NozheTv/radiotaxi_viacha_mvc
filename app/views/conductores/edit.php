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

            <label for="nombre">Nombre:</label>
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
                value="<?php 
                    echo isset($_GET['nombre']) 
                        ? htmlspecialchars($_GET['nombre']) 
                        : htmlspecialchars($conductor['nombre'] ?? ''); 
                ?>" 
            />

            <label for="email">Correo Electrónico:</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                placeholder="conductor@gmail.com" 
                maxlength="40"
                pattern="[a-zA-Z0-9._%+-]+@gmail\.com$"
                title="El email debe ser de Gmail y terminar en .com"
                value="<?php 
                    echo isset($_GET['email']) 
                        ? htmlspecialchars($_GET['email']) 
                        : htmlspecialchars($conductor['email'] ?? ''); 
                ?>" 
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
                title="Solo números, mínimo 8 y máximo 15 dígitos"
                value="<?php 
                    echo isset($_GET['telefono']) 
                        ? htmlspecialchars($_GET['telefono']) 
                        : htmlspecialchars($conductor['telefono'] ?? ''); 
                ?>" 
            />

            <button type="submit">Actualizar Conductor</button>
        </fieldset>
    </form>
</main>

<?php require_once APP_ROOT . '/views/partials/footer.php'; ?>

<script>
// --- BLOQUEAR ESPACIOS EN EMAIL ---
const emailInput = document.getElementById('email');
const nombreInput = document.getElementById('nombre');
const form = document.getElementById('conductorForm');

// Evita escribir espacios en correo
emailInput.addEventListener('keydown', e => {
    if (e.key === ' ') e.preventDefault();
});

// Evita pegar texto con espacios en correo
emailInput.addEventListener('paste', e => {
    const paste = (e.clipboardData || window.clipboardData).getData('text');
    if (paste.includes(' ')) e.preventDefault();
});

// Trim de campos al enviar
form.addEventListener('submit', e => {
    nombreInput.value = nombreInput.value.trim();
    emailInput.value = emailInput.value.trim();

    // Si email contiene espacios intermedios, se bloquea el envío
    if (emailInput.value.includes(' ')) {
        alert('El correo no puede contener espacios.');
        e.preventDefault();
    }
});
</script>
