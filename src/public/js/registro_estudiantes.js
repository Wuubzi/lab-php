const estudiantes = [];
let currentEditId = null;
const programFilter = document.getElementById("programFilter");
const scoreFilter = document.getElementById("scoreFilter");
const searchInput = document.getElementById("searchInput");
async function openModal(editId = null) {
  currentEditId = editId;
  const modal = document.getElementById("clientModal");
  const modalTitle = document.getElementById("modalTitle");
  const submitBtn = document.getElementById("submitBtn");
  const form = document.getElementById("clientForm");

  if (editId) {
    modalTitle.textContent = "Editar Estudiante";
    submitBtn.textContent = "Actualizar Estudiante";
    const estudiante = await getUsuarioById(editId);
    if (estudiante) {
      document.getElementById("nombre").value = estudiante.nombre;
      document.getElementById("apellido").value = estudiante.apellido;
      document.getElementById("usuario").value = estudiante.usuario;
      document.getElementById("email").value = estudiante.email;
      document.getElementById("programa").value = estudiante.programa;
      document.getElementById("calificacion").value = estudiante.calificacion;
    }
  } else {
    modalTitle.textContent = "Nuevo Estudiante";
    submitBtn.textContent = "Crear Estudiante";
    form.reset();
  }

  modal.classList.add("active");
}
function closeModal() {
  document.getElementById("clientModal").classList.remove("active");
  document.getElementById("clientForm").reset();
  currentEditId = null;
}

function handleFilterChangeProgram() {
  const selectedProgram = programFilter.value;
  getEstudianteByPrograma(selectedProgram);
}

function handleFilterChangeCalificacion() {
  const selectCalificacion = scoreFilter.value;
  const [calificacion_inicio, calificacion_final] =
    selectCalificacion.split("-");
  getEstudianteByCalificacion(calificacion_inicio, calificacion_final);
}

searchInput.addEventListener("input", () => {
  const texto = searchInput.value.trim();
  buscarEstudiantes(texto);
});

programFilter.addEventListener("change", handleFilterChangeProgram);
scoreFilter.addEventListener("change", handleFilterChangeCalificacion);

document.addEventListener("DOMContentLoaded", () => {
  getAllEstudiantes();
  const storedId = localStorage.getItem("id_user");
  if (storedId) {
    getProfileImage(storedId);
  }
});

async function getProfileImage(id) {
  const estudiante = await getUsuarioById(id);
  if (estudiante) {
    document.getElementById("profileImage").src = estudiante.profile_url;
  }
}

document.getElementById("profile-circle").addEventListener("click", () => {
  window.location.href = "/estudiantes/perfil";
});

async function getAllEstudiantes() {
  try {
    const response = await fetch("/estudiantes/", { method: "GET" });

    const data = await response.json();

    if (data.status === "success") {
      estudiantes.length = 0;
      data.data.forEach((estudiante) => estudiantes.push(estudiante));
      renderEstudiantes(estudiantes);
    }
  } catch (error) {
    console.error("Error en fetch:", error);
  }
}

function renderEstudiantes(estudiantes) {
  const tbody = document.getElementById("clientsTable");

  tbody.innerHTML = "";

  if (estudiantes.length === 0) {
    tbody.style.display = "none";
    emptyState.style.display = "block";
    return;
  }

  tbody.style.display = "";
  emptyState.style.display = "none";

  tbody.innerHTML = estudiantes
    .map(
      (estudiante) => `
   <tr>
        <td>${estudiante.id}</td>
        <td>${estudiante.nombre}</td>
        <td>${estudiante.apellido}</td>
        <td>${estudiante.email}</td>
        <td>${estudiante.usuario}</td>
        <td>${estudiante.programa}</td>
        <td>${estudiante.calificacion}</td>
        <td>
            <div class="actions">
                <button class="btn btn-secondary btn-sm" onclick="openModal(${estudiante.id})">
                    ✏️ Editar
                </button>
                <button class="btn btn-danger btn-sm" onclick="deleteEstudiante(${estudiante.id})">
                    🗑️ Eliminar
                </button>
            </div>
        </td>
   </tr>
  `
    )
    .join("");
}

async function deleteEstudiante(id) {
  const storedId = localStorage.getItem("id_user");
  const formData = new FormData();
  formData.append("id", id);
  formData.append("id_user_historial", storedId);
  try {
    const response = await fetch("/estudiantes/delete", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      getAllEstudiantes();
      closeModal();
    }
  } catch (error) {
    console.error("Error en fetch:", error);
  }
}

async function createEstudiante() {
  const storedId = localStorage.getItem("id_user");
  const formData = new FormData();
  formData.append("id_user_historial", storedId);
  formData.append("nombre", document.getElementById("nombre").value);
  formData.append("apellido", document.getElementById("apellido").value);
  formData.append("usuario", document.getElementById("usuario").value);
  formData.append("email", document.getElementById("email").value);
  formData.append("programa", document.getElementById("programa").value);
  formData.append(
    "calificacion",
    document.getElementById("calificacion").value
  );

  try {
    const response = await fetch("/estudiantes/create", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      closeModal();
      getAllEstudiantes();
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
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

async function updateEstudiante(id) {
  try {
    const storedId = localStorage.getItem("id_user");
    const formData = new FormData();
    formData.append("id_user_historial", storedId);
    formData.append("id", id);
    formData.append("nombre", document.getElementById("nombre").value);
    formData.append("apellido", document.getElementById("apellido").value);
    formData.append("usuario", document.getElementById("usuario").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("programa", document.getElementById("programa").value);
    formData.append(
      "calificacion",
      document.getElementById("calificacion").value
    );
    const response = await fetch("/estudiantes/update", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      getAllEstudiantes();
      closeModal();
    } else {
      alert(data.message);
      getAllEstudiantes();
      closeModal();
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

async function getEstudianteByCalificacion(
  calificacion_inicio,
  calificacion_final
) {
  if (calificacion_final === undefined || calificacion_inicio === undefined) {
    getAllEstudiantes();
    return;
  }
  try {
    const response = await fetch(
      `/estudiantes/getEstudiantesByCalificaciones?calificacion_inicio=${calificacion_inicio}&calificacion_final=${calificacion_final}`,
      {
        method: "GET",
      }
    );
    const data = await response.json();

    if (data.status === "success") {
      renderEstudiantes(data.data);
    } else {
      renderEstudiantes([]);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

async function buscarEstudiantes(texto) {
  try {
    if (texto.length === 0) {
      getAllEstudiantes();
    }
    const response = await fetch(
      `/estudiantes/getEstudiantesByBusqueda?busqueda=${texto}`,
      {
        method: "GET",
      }
    );
    const data = await response.json();

    if (data.status === "success") {
      renderEstudiantes(data.data);
    } else {
      renderEstudiantes([]);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

async function getEstudianteByPrograma(programa) {
  try {
    if (programa.length === 0) {
      getAllEstudiantes();
    }
    const response = await fetch(
      `/estudiantes/getEstudiantesByPrograma?programa=${programa}`,
      {
        method: "GET",
      }
    );

    const data = await response.json();

    if (data.status === "success") {
      renderEstudiantes(data.data);
    } else {
      renderEstudiantes([]);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

function setupFormSubmission() {
  const form = document.getElementById("clientForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      if (currentEditId) {
        await updateEstudiante(currentEditId);
      } else {
        createEstudiante();
      }
    });
  }
}

setupFormSubmission();
