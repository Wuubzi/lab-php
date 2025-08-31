<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="/public/css/recuperar_contraseña.css">
</head>

<body>
    <div class="recover-container">
        <h1 class="recover-title">Recuperar Contraseña</h1>
        <p class="recover-text">Introduce tu correo electrónico y te enviaremos un código para restablecer tu
            contraseña.</p>
        <form class="recover-form" id="recoverForm">
            <input type="email" class="recover-input" id="email" placeholder="Correo electrónico" required>
            <button type="submit" class="recover-button">Enviar código</button>
        </form>
    </div>
</body>

<script src="/public/js/recuperar_contraseña.js"></script>

</html>