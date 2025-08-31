// Elementos del DOM
const totalStudents = document.getElementById("totalStudents");
const averageGrade = document.getElementById("averageGrade");
const topGrade = document.getElementById("topGrade");
const lowGrade = document.getElementById("lowGrade");
const averageAdsoAverage = document.getElementById("average-adso-average");
const averageAdsoCount = document.getElementById("average-adso-count");
const averageEntrenamientoAverage = document.getElementById(
  "average-entrenamiento-average"
);
const averageEntrenamientoCount = document.getElementById(
  "average-entrenamiento-count"
);
const averageSstAverage = document.getElementById("average-sst-average");
const averageSstCount = document.getElementById("average-sst-count");
const studentsChart = document.getElementById("studentsChart");
const exportListadoPdf = document.getElementById("exportListadoPdf");
const exportListadoExcel = document.getElementById("exportListadoExcel");
const exportRanking = document.getElementById("exportRanking");
document.addEventListener("DOMContentLoaded", () => {
  const storedId = localStorage.getItem("id_user");
  if (storedId) {
    getProfileImage(storedId);
  }
  getDatosGenerales();
  getPromedioPorPrograma();
  getRankingEstudiantes();
});
exportListadoPdf.addEventListener("click", getExportPdf);
exportListadoExcel.addEventListener("click", getExportExcel);
exportRanking.addEventListener("click", getExportRanking);

async function getProfileImage(id) {
  const estudiante = await getUsuarioById(id);
  if (estudiante) {
    document.getElementById("profileImage").src = estudiante.profile_url;
  }
}

async function getUsuarioById(id) {
  try {
    const response = await fetch(`/estudiantes/getEstudianteById?id=${id}`, {
      method: "GET",
    });
    const data = await response.json();
    if (data.status === "success") {
      return data.data;
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

document.getElementById("profile-circle").addEventListener("click", () => {
  window.location.href = "/estudiantes/perfil";
});

async function getDatosGenerales() {
  try {
    const response = await fetch("/estadisticas/getDatosGenerales", {
      method: "GET",
    });
    const data = await response.json();
    if (data.status === "success") {
      const estudiante = data.data;
      totalStudents.textContent = estudiante.total_estudiantes;
      averageGrade.textContent = parseFloat(
        estudiante.promedio_general || 0
      ).toFixed(1);
      topGrade.textContent = estudiante.calificacion_maxima;
      lowGrade.textContent = estudiante.calificacion_minima;
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

async function getPromedioPorPrograma() {
  try {
    const response = await fetch("/estadisticas/getPromedioPorPrograma", {
      method: "GET",
    });

    setDefaultValues();

    const data = await response.json();
    const estudiantes = data.data;

    if (data.status === "success") {
      // Valores iniciales para todos los programas
      let promedioAdso = 0;
      let countAdso = 0;
      let promedioEntrenamiento = 0;
      let countEntrenamiento = 0;
      let promedioSst = 0;
      let countSst = 0;

      // Inicializar todos los elementos con valores por defecto
      setProgramaStats(
        { promedio_programa: 0, total_estudiante_programa: 0 },
        averageAdsoAverage,
        averageAdsoCount
      );
      setProgramaStats(
        { promedio_programa: 0, total_estudiante_programa: 0 },
        averageEntrenamientoAverage,
        averageEntrenamientoCount
      );
      setProgramaStats(
        { promedio_programa: 0, total_estudiante_programa: 0 },
        averageSstAverage,
        averageSstCount
      );

      // Actualizar solo los programas que tienen datos
      estudiantes.forEach((estudiante) => {
        switch (estudiante.programa) {
          case "ADSO":
            setProgramaStats(estudiante, averageAdsoAverage, averageAdsoCount);
            promedioAdso = parseFloat(estudiante.promedio_programa || 0);
            countAdso = estudiante.total_estudiante_programa ?? 0;
            break;

          case "Entrenamiento Deportivo":
            setProgramaStats(
              estudiante,
              averageEntrenamientoAverage,
              averageEntrenamientoCount
            );
            promedioEntrenamiento = parseFloat(
              estudiante.promedio_programa || 0
            );
            countEntrenamiento = estudiante.total_estudiante_programa ?? 0;
            break;

          case "SST":
            setProgramaStats(estudiante, averageSstAverage, averageSstCount);
            promedioSst = parseFloat(estudiante.promedio_programa || 0);
            countSst = estudiante.total_estudiante_programa ?? 0;
            break;
        }
      });

      renderChartPie([countAdso, countEntrenamiento, countSst]);
      renderChartBar([promedioAdso, promedioEntrenamiento, promedioSst]);
    }
  } catch (error) {
    console.error("Error obteniendo promedios:", error);
    // En caso de error, establecer valores por defecto
    setProgramaStats(
      { promedio_programa: 0, total_estudiante_programa: 0 },
      averageAdsoAverage,
      averageAdsoCount
    );
    setProgramaStats(
      { promedio_programa: 0, total_estudiante_programa: 0 },
      averageEntrenamientoAverage,
      averageEntrenamientoCount
    );
    setProgramaStats(
      { promedio_programa: 0, total_estudiante_programa: 0 },
      averageSstAverage,
      averageSstCount
    );
  }
}

function setDefaultValues() {
  // ADSO por defecto
  if (averageAdsoAverage) averageAdsoAverage.textContent = "0.0";
  if (averageAdsoCount) averageAdsoCount.textContent = "0 Estudiantes";

  // Entrenamiento por defecto
  if (averageEntrenamientoAverage)
    averageEntrenamientoAverage.textContent = "0.0";
  if (averageEntrenamientoCount)
    averageEntrenamientoCount.textContent = "0 Estudiantes";

  // SST por defecto
  if (averageSstAverage) averageSstAverage.textContent = "0.0";
  if (averageSstCount) averageSstCount.textContent = "0 Estudiantes";
}

function setProgramaStats(estudiante, averageElement, countElement) {
  const promedio = parseFloat(estudiante.promedio_programa || 0).toFixed(1);
  const total = estudiante.total_estudiante_programa ?? 0;

  averageElement.textContent = promedio;
  countElement.textContent = `${total} ${
    total === 1 ? "Estudiante" : "Estudiantes"
  }`;
}

function renderChartPie(datos) {
  const ctx = studentsChart;

  new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["ADSO", "Entrenamiento", "SST"],
      datasets: [
        {
          label: "Estudiante por programa",
          data: datos,
          backgroundColor: ["#4f46e5", "#22c55e", "#ef4444"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}

function renderChartBar(datos) {
  const ctx = comparisonChart;

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["ADSO", "Entrenamiento", "SST"],
      datasets: [
        {
          label: "Promedio por programa",
          data: datos,
          backgroundColor: ["#4f46e5", "#22c55e", "#ef4444"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}

async function getRankingEstudiantes() {
  try {
    const response = await fetch("/estadisticas/getRankingEstudiantes", {
      method: "GET",
    });
    const data = await response.json();

    if (data.status === "success") {
      const studentsList = document.getElementById("studentsList");
      studentsList.innerHTML = ""; // Limpiar contenido previo

      const topStudents = data.data.slice(0, 5); // Tomar solo los primeros 5

      topStudents.forEach((student, index) => {
        const studentCard = document.createElement("div");
        studentCard.classList.add("student-card");
        if (index < 3) studentCard.classList.add(`rank-${index + 1}`);

        studentCard.innerHTML = `
          <div class="student-rank">${index + 1}</div>
          <div class="student-info">
            <div class="student-name">${student.nombre} ${
          student.apellido
        }</div>
            <div class="student-program">${student.programa}</div>
          </div>
          <div class="student-grade">${parseFloat(student.calificacion).toFixed(
            1
          )}</div>
        `;

        studentsList.appendChild(studentCard);
      });
    }
  } catch (error) {
    console.error("Error cargando ranking:", error);
  }
}

async function getExportPdf() {
  fetch("/estadisticas/Exportpdf", {
    method: "GET",
  })
    .then((res) => res.blob())
    .then((blob) => {
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "estudiantes.pdf";
      a.click();
      URL.revokeObjectURL(url);
    })
    .catch((err) => console.error("Error al descargar PDF:", err));
}

async function getExportExcel() {
  fetch("/estadisticas/ExportExcel", {
    method: "GET",
  })
    .then((res) => res.blob())
    .then((blob) => {
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "estudiantes.csv";
      a.click();
      URL.revokeObjectURL(url);
    })
    .catch((err) => console.error("Error al descargar PDF:", err));
}

async function getExportRanking() {
  fetch("/estadisticas/exportRanking", {
    method: "GET",
  })
    .then((res) => res.blob())
    .then((blob) => {
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "estudiantes.pdf";
      a.click();
      URL.revokeObjectURL(url);
    })
    .catch((err) => console.error("Error al descargar PDF:", err));
}
