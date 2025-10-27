<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Administrador</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/login.css" />
</head>
<body>

<div class="login-container">
    <img src="<?php echo BASE_URL; ?>img/imagen31deenero.jpg" alt="">
    <h1>Login Administrador</h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>auth/login" method="post" class="login-form" id="loginForm">
        <label for="email">Correo electrónico:</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            required 
            placeholder="ejemplo@gmail.com" 
            maxlength="30"
            pattern="[a-zA-Z0-9._%+-]+@gmail\.com"
            title="Debe ser un correo Gmail válido, sin espacios"
        />

        <label for="password">Contraseña:</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            placeholder="Contraseña" 
            maxlength="20"
        />

        <button type="submit" class="btn-login">Iniciar Sesión</button>
    </form>
</div>

<script>
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    function blockSpaces(event) {
        if (event.key === ' ') {
            event.preventDefault(); // Bloquea la tecla espacio
        }
    }

    emailInput.addEventListener('keydown', blockSpaces);
    passwordInput.addEventListener('keydown', blockSpaces);
</script>

</body>
</html>
