document.addEventListener("DOMContentLoaded", () => {
  const storedId = localStorage.getItem("id_user");
  if (storedId) {
    getProfileImage(storedId);
  }
  getHistorial();
});

async function getProfileImage(id) {
  const estudiante = await getUsuarioById(id);
  if (estudiante) {
    document.getElementById("profileImage").src = estudiante.profile_url;
  }
}

async function getHistorial() {
  try {
    const response = await fetch("/estudiantes/historial", {
      method: "GET",
    });

    const data = await response.json();

    if (data.status === "success") {
      const tbody = document.getElementById("tableBody");
      tbody.innerHTML = ""; // Limpiar tabla antes de insertar

      data.data.forEach((item) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td><span class="record-id">${item.id_registro}</span></td>
          <td>
            <div class="user-info">
              <div>
                <div>${item.usuario}</div>
                <div style="font-size: 0.8rem; color: #999;">${item.email}</div>
              </div>
            </div>
          </td>
          <td><span class="action-badge action-${item.accion.toLowerCase()}">${capitalize(
          item.accion
        )}</span></td>
          <td><span class="timestamp">${item.fecha_hora}</span></td>
          <td>${item.tabla_recurso}</td>
          <td>${item.descripcion}</td>
          <td><span class="timestamp">${item.ip_address}</span></td>
        `;

        tbody.appendChild(tr);
      });
    }
  } catch (error) {
    console.error("Error cargando historial:", error);
  }
}

// Función para capitalizar la primera letra
function capitalize(text) {
  return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
}

document.getElementById("profile-circle").addEventListener("click", () => {
  window.location.href = "/estudiantes/perfil";
});

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
