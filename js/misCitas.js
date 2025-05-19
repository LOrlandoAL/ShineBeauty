document.addEventListener("DOMContentLoaded", () => {
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  const contenedor = document.getElementById("contenedor-citas");

  if (!usuario || !usuario.id) {
    alert("Debes iniciar sesión para ver tus citas.");
    window.location.href = "login.html";
    return;
  }

  const esAdmin = usuario.tipo === "admin";
  const url = esAdmin ? "datos/ObtenerTodasLasCitas.php" : "datos/ObtenerCitasUsuario.php";

  fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ idUsuario: usuario.id })
  })
    .then((res) => res.json())
    .then((citas) => {
      if (!Array.isArray(citas)) throw new Error("Respuesta inesperada del servidor");
      mostrarCitas(citas);
    })
    .catch((err) => {
      console.error("Error al cargar citas:", err);
      contenedor.innerHTML = `<p style="color:red;">Ocurrió un error al cargar tus citas.</p>`;
    });

  function mostrarCitas(citas) {
    if (citas.length === 0) {
      contenedor.innerHTML = `<p>No hay citas registradas.</p>`;
      return;
    }

    const agrupadas = agruparPorFecha(citas);
    contenedor.innerHTML = "";

    Object.keys(agrupadas)
      .sort((a, b) => b.localeCompare(a)) // fechas descendente
      .forEach((fecha) => {
        const bloque = document.createElement("div");
        bloque.classList.add("bloque-fecha");
        bloque.innerHTML = `<h3>${fecha}</h3>`;

        agrupadas[fecha]
          .sort((a, b) => b.hora.localeCompare(a.hora))
          .forEach((cita) => {
            const tarjeta = document.createElement("div");
            tarjeta.classList.add("cita-card");

            tarjeta.innerHTML = `
              <div class="cita-info">
                <p><strong>Hora:</strong> ${cita.hora}</p>
                <p><strong>Servicio(s):</strong> ${Array.isArray(cita.servicios) ? cita.servicios.join(", ") : cita.servicios}</p>
                ${esAdmin ? `
                  <p><strong>Cliente:</strong> ${cita.nombreCliente}</p>
                  <p><strong>Tel:</strong> ${cita.telefonoCliente}</p>
                  <p><strong>Email:</strong> ${cita.correoCliente}</p>
                ` : ""}
              </div>
              <div class="cita-acciones">
                <button class="btn-eliminar" onclick="eliminarCita(${cita.idCita})">Eliminar</button>
              </div>
            `;

            bloque.appendChild(tarjeta);
          });

        contenedor.appendChild(bloque);
      });
  }

  function agruparPorFecha(citas) {
    const agrupadas = {};
    citas.forEach((c) => {
      if (!agrupadas[c.fecha]) agrupadas[c.fecha] = [];
      agrupadas[c.fecha].push(c);
    });
    return agrupadas;
  }

  window.eliminarCita = function (idCita) {
    if (!confirm("¿Estás seguro de eliminar esta cita?")) return;

    fetch("datos/EliminarCita.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ idCita })
    })
      .then((res) => res.json())
      .then((res) => {
        alert(res.mensaje || "Cita eliminada correctamente.");
        location.reload();
      })
      .catch((err) => {
        console.error("Error al eliminar cita:", err);
        alert("No se pudo eliminar la cita.");
      });
  };
});
