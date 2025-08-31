async function verificar() {
  const formData = new FormData();
  formData.append("code", document.getElementById("code").value);

  try {
    const response = await fetch("/auth/validateCode", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      window.location.href = "/auth/updatePassword";
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
  const form = document.getElementById("verifyForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      await verificar();
    });
  }
}

setupFormSubmission();
