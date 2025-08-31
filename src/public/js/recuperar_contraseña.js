async function recuperar() {
  const formData = new FormData();
  formData.append("email", document.getElementById("email").value);

  try {
    const response = await fetch("/auth/forgotPassword", {
      method: "POST",
      body: formData,
    });
    const data = await response.json();
    if (data.status === "success") {
      alert(data.message);
      window.location.href = "/auth/validateCode";
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
  const form = document.getElementById("recoverForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      await recuperar();
    });
  }
}

setupFormSubmission();
