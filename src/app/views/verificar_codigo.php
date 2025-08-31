<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código</title>
    <link rel="stylesheet" href="/public/css/verificar_codigo.css">
</head>

<body>
    <div class="verify-container">
        <h1 class="verify-title">Verificar Código</h1>
        <p class="verify-text">Introduce el código que hemos enviado a tu correo.</p>
        <form class="verify-form" id="verifyForm">
            <input type="text" id="code" class="verify-input" placeholder="Código de verificación" required>
            <button type="submit" class="verify-button">Verificar</button>
        </form>
    </div>
</body>

<script src="/public/js/verificar_codigo.js"></script>

</html>