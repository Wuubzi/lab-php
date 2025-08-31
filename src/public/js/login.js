async function login() {
  const formData = new FormData();
  formData.append("usuario", document.getElementById("usuario").value);
  formData.append("contrasena", document.getElementById("contrasena").value);

  try {
    const response = await fetch("/auth/login", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      localStorage.setItem("id_user", data.id_user);
      localStorage.setItem("usuario", data.usuario);
      window.location.href = "/home";
    } else if (data.status === "error") {
      if (data.errors && Array.isArray(data.errors)) {
        alert("Errores encontrados:\n\n" + data.errors.join("\n"));
      } else {
        alert(data.message || "Credenciales inválidas.");
      }
    } else {
      alert(data.message || "Ocurrió un error inesperado");
    }
  } catch (error) {
    console.error("Error de conexión o JSON inválido:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

function setupFormSubmission() {
  const form = document.getElementById("loginForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      await login();
    });
  }
}

setupFormSubmission();
