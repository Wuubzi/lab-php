// Elementos del DOM
const photoInput = document.getElementById("photoInput");
const profileImage = document.getElementById("profileImage");
const photoContainer = document.querySelector(".photo-container");
const removePhotoBtn = document.getElementById("removePhotoBtn");
const profileForm = document.getElementById("profileForm");
const clearBtn = document.getElementById("clearBtn");
const logoutBtn = document.getElementById("logoutBtn");

// Event listeners
photoContainer.addEventListener("click", () => photoInput.click());
logoutBtn.addEventListener("click", Logout);
photoInput.addEventListener("change", handleImageUpload);
removePhotoBtn.addEventListener("click", removePhoto);
clearBtn.addEventListener("click", clearForm);
function handleImageUpload(e) {
  const file = e.target.files[0];
  console.log("Archivo seleccionado:", file);
  if (!file) return;

  if (!file.type.startsWith("image/")) {
    alert("Por favor selecciona un archivo de imagen válido.");
    return;
  }

  const reader = new FileReader();
  reader.onload = function (event) {
    profileImage.src = event.target.result;
    removePhotoBtn.style.display = "block";
  };
  reader.readAsDataURL(file);
}

function removePhoto() {
  profileImage.src =
    "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDE1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiBmaWxsPSIjRTNGMkZEIi8+CjxjaXJjbGUgY3g9Ijc1IiBjeT0iNjAiIHI9IjI1IiBmaWxsPSIjOTNCQkU5Ii8+CjxwYXRoIGQ9Ik0zMCAxMzBDMzAgMTEwIDUwIDk1IDc1IDk1UzEyMCAxMTAgMTIwIDEzMFYxNTBIMzBWMTMwWiIgZmlsbD0iIzkzQkJFOSIvPgo8L3N2Zz4=";
  removePhotoBtn.style.display = "none";
  photoInput.value = "";
}

function clearForm() {
  profileForm.reset();
  removePhoto();
}

document.addEventListener("DOMContentLoaded", () => {
  const id = localStorage.getItem("id_user");
  if (id) {
    getUsuarioById(id);
  } else {
    console.warn("No hay id_user en localStorage");
  }
});

async function getUsuarioById(id) {
  try {
    const response = await fetch(`/estudiantes/getEstudianteById?id=${id}`, {
      method: "GET",
    });
    const data = await response.json();
    if (data.status === "success") {
      const estudiante = data.data;
      document.getElementById("nombre").value = estudiante.nombre;
      document.getElementById("apellido").value = estudiante.apellido;
      document.getElementById("usuario").value = estudiante.usuario;
      document.getElementById("email").value = estudiante.email;
      document.getElementById("programa").value = estudiante.programa;
      if (estudiante.profile_url) {
        document.getElementById("profileImage").src = estudiante.profile_url;
      }
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

async function updateEstudiante(id) {
  try {
    const formData = new FormData();
    formData.append("id_user_historial", id);
    formData.append("id", id);
    formData.append("nombre", document.getElementById("nombre").value);
    formData.append("apellido", document.getElementById("apellido").value);
    formData.append("usuario", document.getElementById("usuario").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("programa", document.getElementById("programa").value);
    if (photoInput.files.length > 0) {
      console.log("Agregando al formData:", photoInput.files[0]); // <--- LOG
      formData.append("foto", photoInput.files[0]);
    }

    const response = await fetch("/estudiantes/update", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      window.location.href = "/estudiantes/perfil";
    } else {
      alert(data.message);
      window.location.href = "/estudiantes/perfil";
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

function setupFormSubmission() {
  const form = document.getElementById("profileForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      const id = localStorage.getItem("id_user");
      if (id) {
        await updateEstudiante(id);
      }
    });
  }
}

setupFormSubmission();

async function Logout() {
  try {
    const response = await fetch("/auth/logout", {
      method: "GET",
    });

    const data = await response.json();
    if (data.status === "success") {
      window.location.href = "/auth/login";
    }
  } catch {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}
