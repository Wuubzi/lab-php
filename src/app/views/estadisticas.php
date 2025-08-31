<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadisticas</title>
    <link rel="stylesheet" href="/public/css/estadisticas.css">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <div class="nav-links">
                <a href="/" class="nav-link" data-page="home">Home</a>
                <a href="/estadisticas" class="nav-link active" data-page="stats">Estadísticas</a>
                <a href="/historial" class="nav-link" data-page="historial">Historial</a>
            </div>
            <button class="profile-circle" id="profile-circle">
                <img id="profileImage" src="" alt="">
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Dashboard Section -->
            <section id="dashboard" class="section active">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="totalStudents"></h3>
                            <p class="stat-label">Total Estudiantes</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="averageGrade"></h3>
                            <p class="stat-label">Promedio General</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏆</div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="topGrade"></h3>
                            <p class="stat-label">Mejor Calificación</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📉</div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="lowGrade"></h3>
                            <p class="stat-label">Menor Calificación</p>
                        </div>
                    </div>
                </div>

                <!-- Program Averages -->
                <div class="program-averages">
                    <h3 class="subsection-title">Promedio por Programa</h3>
                    <div class="program-grid" id="programAverages">
                        <div class="program-card">
                            <h4>ADSO</h4>
                            <div class="program-average" id="average-adso-average"></div>
                            <span class="program-count" id="average-adso-count"></span>
                        </div>
                        <div class="program-card">
                            <h4>Entrenamiento Deportivo</h4>
                            <div class="program-average" id="average-entrenamiento-average"></div>
                            <span class="program-count" id="average-entrenamiento-count"></span>
                        </div>
                        <div class="program-card">
                            <h4>SST</h4>
                            <div class="program-average" id="average-sst-average"></div>
                            <span class="program-count" id="average-sst-count"></span>
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="charts-grid">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Estudiantes por Programa</h3>

                        </div>
                        <div class="chart-wrapper">
                            <canvas id="studentsChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Comparativo entre Programas</h3>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="comparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Students Section -->
            <section id="students" class="section active">

                <!-- Top 5 Students -->
                <div class="ranking-container">
                    <div class="ranking-header">
                        <h3 class="ranking-title" id="rankingTitle">Top 5 Estudiantes</h3>
                    </div>
                    <div class="students-list" id="studentsList">
                    </div>
                </div>


            </section>

            <!-- Reports Section -->
            <section id="reports" class="section active">
                <div class="section-header">
                    <h2 class="section-title">Reportes y Exportación</h2>
                </div>

                <div class="reports-grid">
                    <div class="report-card">
                        <div class="report-icon">📄</div>
                        <h3 class="report-title">Listado de Estudiantes</h3>
                        <p class="report-description">Exportar listado completo de estudiantes con sus calificaciones
                        </p>
                        <div class="report-actions">
                            <button class="btn-secondary" id="exportListadoPdf">Exportar PDF</button>
                            <button class="btn-secondary" id="exportListadoExcel">Exportar Excel</button>
                        </div>
                    </div>
                    <div class="report-card">
                        <div class="report-icon">🏆</div>
                        <h3 class="report-title">Ranking por Programa</h3>
                        <p class="report-description">Rankings individuales de cada programa académico</p>
                        <div class="report-actions">
                            <button class="btn-secondary" id="exportRanking">Exportar Ranking</button>
                        </div>
                    </div>
                </div>


            </section>
        </div>
    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/public/js/estadisticas.js"></script>


</html>