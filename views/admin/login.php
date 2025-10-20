<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
</head>
<body>
    <div class="login-container">
        <h2>Login Administrador</h2>
        <form method="post" action="/admin/login">
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu correo" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Ingresar</button>
            </div>
        </form>
    </div>
</body>
</html>
