<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Iniciar Sesión</h1>
                <p>Ingresa tus credenciales para continuar</p>
            </div>

            <form id="loginForm" class="login-form">
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required>
                    <span class="input-error" id="usuarioError"></span>
                </div>

                <div class="input-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                    <span class="input-error" id="contrasenaError"></span>
                </div>

                <div class="form-options">
                    <a href="/auth/forgotPassword" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="login-button">
                    <span class="button-text">Iniciar Sesión</span>
                    <div class="loading-spinner" style="display: none;"></div>
                </button>
            </form>

            <div class="login-footer">
                <p>¿No tienes una cuenta? <a href="/auth/register" class="signup-link">Regístrate aquí</a></p>
            </div>
        </div>
    </div>

    <script src="/public/js/login.js"></script>
</body>

</html>