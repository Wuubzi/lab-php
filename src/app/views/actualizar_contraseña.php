<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Contraseña</title>
    <link rel="stylesheet" href="/public/css/actualizar_contraseña.css">
</head>

<body>
    <div class="update-container">
        <h1 class="update-title">Actualizar Contraseña</h1>
        <p class="update-text">Introduce tu nueva contraseña y confírmala.</p>
        <form class="update-form" id="updateForm">
            <input type="password" class="update-input" id="contrasena" placeholder="Nueva contraseña" required>
            <input type="password" class="update-input" id="confirmarContrasena" placeholder="Confirmar contraseña"
                required>
            <button type="submit" class="update-button">Actualizar</button>
        </form>
    </div>
</body>

<script src="/public/js/actualizar_contraseña.js"></script>

</html>