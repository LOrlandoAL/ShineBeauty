document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formLogin");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("datos/login.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Guardamos el usuario completo en localStorage
          localStorage.setItem("usuario", JSON.stringify(data.usuario));

          alert("Bienvenido " + data.usuario.nombre);

          // Redirige según el tipo de usuario
          if (data.usuario.tipo === "admin") {
            window.location.href = "admin.html";
          } else {
            window.location.href = "index.html";
          }
        } else {
          alert(data.mensaje);
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Error en el servidor");
      });
  });
});
