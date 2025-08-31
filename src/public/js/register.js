async function register() {
  const formData = new FormData();
  formData.append("nombre", document.getElementById("nombre").value);
  formData.append("apellido", document.getElementById("apellido").value);
  formData.append("usuario", document.getElementById("usuario").value);
  formData.append("email", document.getElementById("email").value);
  formData.append("contrasena", document.getElementById("contrasena").value);
  formData.append("programa", document.getElementById("programa").value);
  try {
    const response = await fetch("/auth/register", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      window.location.href = "/home";
    } else if (data.status === "error") {
      alert("Errores encontrados:\n\n" + data.errors.join("\n"));
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

function setupFormSubmission() {
  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      await register();
    });
  }
}

setupFormSubmission();
