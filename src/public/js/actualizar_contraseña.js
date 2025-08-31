async function actualizar() {
  const formData = new FormData();
  formData.append("contrasena", document.getElementById("contrasena").value);
  formData.append(
    "confirmarContrasena",
    document.getElementById("confirmarContrasena").value
  );

  try {
    const response = await fetch("/auth/updatePassword", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      window.location.href = "/auth/login";
    } else if (data.status === "error") {
      alert(data.message);
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión. Por favor, intenta de nuevo.");
  }
}

function setupFormSubmission() {
  const form = document.getElementById("updateForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      await actualizar();
    });
  }
}

setupFormSubmission();
