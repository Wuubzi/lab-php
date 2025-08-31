<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cambios</title>
    <link rel="stylesheet" href="/public/css/historial.css">
</head>
<header class="header">
    <nav class="nav-container">
        <div class="nav-links">
            <a href="/" class="nav-link" data-page="home">Home</a>
            <a href="/estadisticas" class="nav-link" data-page="stats">Estadísticas</a>
            <a href="/historial" class="nav-link active" data-page="historial">Historial</a>
        </div>
        <button class="profile-circle" id="profile-circle">
            <img id="profileImage" src="" alt="">
        </button>
    </nav>
</header>

<body>
    <div class="container">
        <div class="table-container">
            <table id="auditTable">
                <thead>
                    <tr>
                        <th>ID Registro</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Fecha y Hora</th>
                        <th>Tabla/Recurso</th>
                        <th>Descripción</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
        </div>

    </div>

</body>

<script src="/public/js/historial.js"></script>

</html>