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
    <title>Dashboard - Gestión de Estudiantes</title>
    <link rel="stylesheet" href="/public/css/registro_estudiantes.css">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <div class="nav-links">
                <a href="/" class="nav-link active" data-page="home">Home</a>
                <a href="/estadisticas/" class="nav-link" data-page="stats">Estadísticas</a>
                <a href="/historial/" class="nav-link" data-page="historial">Historial</a>
            </div>
            <button class="profile-circle" id="profile-circle">
                <img id="profileImage" src="" alt="">
            </button>
        </nav>
    </header>

    <!-- Main Container -->
    <main class="main-container">
        <!-- Home Page -->
        <div id="home" class="page-section active">
            <h1 class="page-title">Gestión de Estudiantes</h1>

            <!-- Controls -->
            <div class="controls">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" class="search-input" id="searchInput"
                        placeholder="Buscar clientes por nombre, email o teléfono...">
                </div>

                <div class="filter-group">
                    <select class="filter-select" id="programFilter">
                        <option value="">Todos los programas</option>
                        <option value="adso">ADSO</option>
                        <option value="entrenamiento deportivo">Entrenamiento Deportivo</option>
                        <option value="sst">SST</option>
                    </select>

                    <select class="filter-select" id="scoreFilter">
                        <option value="">Todas las calificaciones</option>
                        <option value="4-5">4-5</option>
                        <option value="3-3.9">3-3.9</option>
                        <option value="2-2.9">2-2.9</option>
                        <option value="0 - 1.9">Menos de 2</option>
                    </select>
                </div>

                <button class="btn btn-primary" onclick="openModal()">
                    ➕ Nuevo Estudiante
                </button>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>usuario</th>
                            <th>Programa</th>
                            <th>Calificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="clientsTable">
                        <!-- Datos se cargan dinámicamente -->
                    </tbody>
                </table>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <div class="empty-state-icon">📄</div>
                    <p>No se encontraron estudiantes</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Nuevo Estudiante</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>

            <form id="clientForm">
                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-input" id="nombre" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Apellido</label>
                    <input type="text" class="form-input" id="apellido" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <input type="text" class="form-input" id="usuario" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" id="email" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Programa</label>
                    <select class="form-input" id="programa" required>
                        <option value="">Seleccionar programa</option>
                        <option value="ADSO">ADSO</option>
                        <option value="Entrenamiento Deportivo">Entrenamiento Deportivo</option>
                        <option value="SST">SST</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Calificación (0-5)</label>
                    <input type="number" class="form-input" id="calificacion" min="0" max="5" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="/public/js/registro_estudiantes.js"></script>

</html>