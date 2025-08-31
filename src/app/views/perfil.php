<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: /auth/login");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="/public/css/perfil.css">
</head>

<body>
    <div class="container">
        <div class="logout-section">
            <button class="logout-btn" id="logoutBtn">🚪 Cerrar sesión</button>
        </div>
        <div class="profile-card">
            <!-- Sección de volver atrás -->
            <div class="back-section">
                <a href="javascript:history.back()" class="back-link">
                    <span class="back-arrow">⬅</span> Atrás
                </a>
            </div>
            <h1 class="title">Mi Perfil</h1>

            <!-- Sección de foto de perfil -->
            <div class="photo-section">
                <div class="photo-container">
                    <img id="profileImage"
                        src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDE1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiBmaWxsPSIjRTNGMkZEIi8+CjxjaXJjbGUgY3g9Ijc1IiBjeT0iNjAiIHI9IjI1IiBmaWxsPSIjOTNCQkU5Ii8+CjxwYXRoIGQ9Ik0zMCAxMzBDMzAgMTEwIDUwIDk1IDc1IDk1UzEyMCAxMTAgMTIwIDEzMFYxNTBIMzBWMTMwWiIgZmlsbD0iIzkzQkJFOSIvPgo8L3N2Zz4="
                        alt="Foto de perfil">
                    <div class="photo-overlay">
                        <i class="camera-icon">📷</i>
                        <span>Cambiar foto</span>
                    </div>
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;">
                <div class="photo-controls">
                    <button id="removePhotoBtn" class="btn-danger" style="display: none;">Eliminar Foto</button>
                </div>
            </div>

            <!-- Formulario de perfil -->
            <form class="profile-form" id="profileForm">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ingresa tu apellido">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="correo@ejemplo.com">
                </div>

                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Nombre de usuario">
                </div>

                <div class="form-group">
                    <label for="programa">Programa</label>
                    <select id="programa" name="programa">
                        <option value="">Selecciona un programa</option>
                        <option value="ADSO">ADSO</option>
                        <option value="Entrenamiento Deportivo">Entrenamiento Deportivo</option>
                        <option value="SST">SST</option>
                    </select>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primary">Guardar Perfil</button>
                    <button type="button" id="clearBtn" class="btn-secondary">Limpiar</button>
                </div>
            </form>
        </div>
    </div>


    <script src="/public/js/perfil.js"></script>
</body>

</html>