document.addEventListener("DOMContentLoaded", () => {
  const contenedorPreview = document.getElementById("preview-servicios");
  const contenedorCatalogo = document.getElementById("servicios-lista");

  // 👉 Función: Mostrar catálogo de servicios por categoría
  const categoriaActual = "Corte"; // Cambiar según la página actual

  if (contenedorCatalogo) {
    fetch(`datos/ObtenerServicios.php?tipo=${categoriaActual}`)
      .then(res => res.json())
      .then(data => mostrarCatalogo(data))
      .catch(err => console.error("Error al cargar catálogo:", err));

    function mostrarCatalogo(servicios) {
      contenedorCatalogo.innerHTML = "";

      servicios.forEach(servicio => {
        const card = document.createElement("div");
        card.classList.add("servicio-card");

        const img = document.createElement("img");
        img.src = servicio.imagen;
        img.alt = servicio.nombre;

        const info = document.createElement("div");
        info.classList.add("servicio-info");
        info.innerHTML = `
          <h3>${servicio.nombre}</h3>
          <p><strong>Precio:</strong> $${servicio.precio}</p>
          <p><strong>Tiempo:</strong> ${servicio.tiempo} hr</p>
          <p>${servicio.descripcion}</p>
        `;

        const btnAgregar = document.createElement("button");
        btnAgregar.textContent = "Agregar a Agenda";
        btnAgregar.classList.add("btn-agregar");
        btnAgregar.onclick = () => agregarAServiciosAgenda(servicio.idServicios, servicio.nombre);

        card.appendChild(img);
        card.appendChild(info);
        card.appendChild(btnAgregar);

        contenedorCatalogo.appendChild(card);
      });
    }

    function agregarAServiciosAgenda(idServicio, nombreServicio) {
      let seleccionados = JSON.parse(localStorage.getItem("serviciosAgenda")) || [];

      if (!seleccionados.includes(idServicio)) {
        seleccionados.push(idServicio);
        localStorage.setItem("serviciosAgenda", JSON.stringify(seleccionados));
        alert(`✅ Servicio "${nombreServicio}" agregado a la agenda.`);
      } else {
        alert(`⚠️ El servicio "${nombreServicio}" ya está en la agenda.`);
      }
    }
  }

  // 👉 Función: Mostrar resumen de servicios agendados desde localStorage
  if (contenedorPreview) {
    const serviciosGuardados = JSON.parse(localStorage.getItem("serviciosAgenda")) || [];
    const usuario = JSON.parse(localStorage.getItem("usuario"));

    if (usuario) {
      document.getElementById('nombre').value = usuario.nombre || '';
      document.getElementById('telefono').value = usuario.telefono || '';
      document.getElementById('correo').value = usuario.correo_electronico || '';
    }

    if (serviciosGuardados.length === 0) {
      contenedorPreview.innerHTML = `<p style="margin-left: 2rem;">No hay servicios agregados a la agenda aún.</p>`;
      return;
    }

    fetch("datos/ObtenerServiciosPorIds.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ids: serviciosGuardados })
    })
      .then(res => res.json())
      .then(servicios => renderizarResumen(servicios))
      .catch(err => console.error("Error al cargar resumen:", err));

    function renderizarResumen(servicios) {
      contenedorPreview.innerHTML = "";
      servicios.forEach((servicio, index) => {
        const card = document.createElement("div");
        card.classList.add("servicio-card");

        card.innerHTML = `
          <div class="servicio-resumen-horizontal">
            <img src="${servicio.imagen}" alt="${servicio.nombre}" />
            <div class="detalle">
              <h3>${servicio.nombre}</h3>
              <p><strong>💰 Precio:</strong> $${servicio.precio} MXN</p>
              <p><strong>⏳ Tiempo:</strong> ${servicio.tiempo} hr</p>
              <p>${servicio.descripcion}</p>
              <input type="hidden" name="citas[${index}][servicios][]" value="${servicio.idServicios}">
            </div>
            <button class="btn-eliminar-servicio" title="Eliminar" data-id="${servicio.idServicios}">🗑️</button>
          </div>
          <hr>
        `;
        contenedorPreview.appendChild(card);
      });

      document.querySelectorAll(".btn-eliminar-servicio").forEach(btn => {
        btn.addEventListener("click", e => {
          const id = parseInt(e.currentTarget.dataset.id);
          const nuevos = serviciosGuardados.filter(sid => sid !== id);
          localStorage.setItem("serviciosAgenda", JSON.stringify(nuevos));
          location.reload();
        });
      });
    }

    function generarHoras() {
      let opciones = '';
      for (let h = 6; h <= 18; h++) {
        const hora = `${h.toString().padStart(2, '0')}:00`;
        opciones += `<option value="${hora}">${hora}</option>`;
      }
      return opciones;
    }
  }
});
