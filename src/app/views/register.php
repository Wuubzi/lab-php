<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="/public/css/register.css">
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1>Crear Cuenta</h1>
                <p>Completa los campos para registrarte</p>
            </div>

            <form id="registerForm" class="register-form">
                <div class="form-row">
                    <div class="input-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                        <span class="input-error" id="firstNameError"></span>
                    </div>

                    <div class="input-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" required>
                        <span class="input-error" id="lastNameError"></span>
                    </div>
                </div>

                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required>
                    <span class="input-error" id="emailError"></span>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <span class="input-error" id="passwordError"></span>
                </div>

                <div class="input-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="***********">
                    <span class="input-error" id="phoneError"></span>
                </div>

                <div class="input-group">
                    <label for="programa">Programa</label>
                    <input type="text" id="programa" name="programa" required>
                    <span class="input-error" id="passwordError"></span>
                </div>

                <button type="submit" class="register-button">
                    <span class="button-text">Crear Cuenta</span>
                    <div class="loading-spinner" style="display: none;"></div>
                </button>
            </form>

            <div class="register-footer">
                <p>¿Ya tienes una cuenta? <a href="/auth/login" class="login-link">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

    <script src="/public/js/register.js"></script>
</body>

</html>