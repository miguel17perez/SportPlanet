document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");

  if (!registerForm) return;

  registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const username = document.getElementById("username").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      if (!username || !email || !password) {
          alert("Todos los campos son obligatorios");
          return;
      }

      try {
          const response = await fetch("/users/register", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ username, email, password }),
          });

          const data = await response.json();
          alert(data.message);

          if (response.ok) window.location.href = "/pages/inicio.html";
      } catch (error) {
          console.error("Error al registrar:", error);
          alert("Error al conectar con el servidor.");
      }
  });
});
