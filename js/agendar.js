document.addEventListener("DOMContentLoaded", function () {
  const usuario = JSON.parse(localStorage.getItem('usuario'));
  const serviciosGuardados = JSON.parse(localStorage.getItem('servicios_agenda')) || [];
  const fechaActual = new Date();
  const fechaMaxima = new Date();
  fechaMaxima.setDate(fechaActual.getDate() + 21);

  const bloqueCitas = document.getElementById('bloques-citas');
  const btnAgregarCita = document.querySelector('.btn-agregar-cita');
  const previewContainer = document.createElement('div');
  previewContainer.id = "preview-servicios";
  previewContainer.style.margin = "30px 0";
  bloqueCitas.parentNode.insertBefore(previewContainer, bloqueCitas);

  if (usuario) {
    document.getElementById('nombre').value = usuario.Usuario;
    // Puedes mapear más campos como correo y teléfono aquí
  }

  let serviciosDisponibles = [];

  fetch("datos/obtenerServicios.php")
    .then(response => response.json())
    .then(data => {
      serviciosDisponibles = data;
      agregarBloqueCita();
      mostrarPreviewServicios();
    })
    .catch(err => {
      console.error("Error al cargar servicios:", err);
    });

  // Mostrar preview de servicios guardados
  const serviciosPreview = document.createElement("div");
  serviciosPreview.id = "preview-servicios";
  serviciosPreview.style.margin = "20px 0";
  document.querySelector(".formulario-agendar").prepend(serviciosPreview);

  mostrarServiciosPreview();

  function mostrarServiciosPreview() {
  const idsSeleccionados = JSON.parse(localStorage.getItem("serviciosAgenda")) || [];

  if (idsSeleccionados.length === 0) {
    serviciosPreview.innerHTML = "<p>No hay servicios agregados a la agenda aún.</p>";
    return;
  }

  const serviciosSeleccionados = serviciosDisponibles.filter(s =>
    idsSeleccionados.includes(parseInt(s.idServicios))
  );

  serviciosPreview.innerHTML = "<h2>Servicios Seleccionados</h2>";

  serviciosSeleccionados.forEach(servicio => {
    const card = document.createElement("div");
    card.classList.add("servicio-preview-card");
    card.style.display = "flex";
    card.style.alignItems = "center";
    card.style.gap = "10px";
    card.style.marginBottom = "10px";

    card.innerHTML = `
      <img src="${servicio.imagen}" alt="${servicio.nombre}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
      <div>
        <strong>${servicio.nombre}</strong><br>
        <small>${servicio.descripcion}</small>
      </div>
      <button style="margin-left:auto;" onclick="eliminarServicioAgenda(${servicio.idServicios})">❌</button>
    `;

    serviciosPreview.appendChild(card);
  });
}

  function eliminarServicioAgenda(id) {
    let servicios = JSON.parse(localStorage.getItem("serviciosAgenda")) || [];
    servicios = servicios.filter(s => s !== id);
    localStorage.setItem("serviciosAgenda", JSON.stringify(servicios));
    mostrarServiciosPreview();
  }


  btnAgregarCita.addEventListener('click', agregarBloqueCita);

  function agregarBloqueCita() {
    const index = bloqueCitas.children.length;

    const bloque = document.createElement('div');
    bloque.classList.add('bloque-cita');
    bloque.style.marginBottom = '20px';
    bloque.style.border = '1px solid var(--color-medio)';
    bloque.style.padding = '15px';
    bloque.style.borderRadius = '8px';
    bloque.style.backgroundColor = 'var(--color-muy-claro)';

    bloque.innerHTML = `
      <div class="campo">
        <label>Fecha:</label>
        <input type="date" name="citas[${index}][fecha]" required min="${fechaActual.toISOString().split("T")[0]}" max="${fechaMaxima.toISOString().split("T")[0]}">
      </div>
      <div class="campo">
        <label>Hora:</label>
        <select name="citas[${index}][hora]" required>
          ${generarOpcionesHora()}
        </select>
      </div>
      <div class="campo">
        <label>Servicios:</label>
        <select name="citas[${index}][servicios][]" multiple required>
          ${serviciosDisponibles.map(servicio => `<option value="${servicio.idServicios}">${servicio.nombre}</option>`).join('')}
        </select>
      </div>
    `;

    bloqueCitas.appendChild(bloque);
  }

  function mostrarPreviewServicios() {
    if (serviciosGuardados.length === 0) {
      previewContainer.innerHTML = `<p>No has agregado servicios a la agenda todavía.</p>`;
      return;
    }

    const htmlServicios = serviciosGuardados.map(id => {
      const s = serviciosDisponibles.find(serv => serv.idServicios == id);
      if (!s) return '';
      return `
        <div style="display:flex; align-items:center; gap:15px; margin-bottom:10px; border-bottom:1px solid #ccc; padding-bottom:10px;">
          <img src="${s.imagen}" alt="${s.nombre}" style="width:80px; height:80px; border-radius:8px; object-fit:cover;">
          <div>
            <strong>${s.nombre}</strong><br>
            <small>${s.descripcion}</small><br>
            <span>Precio: $${s.precio} MXN | Tiempo: ${s.tiempo} hr</span>
          </div>
        </div>
      `;
    }).join('');

    previewContainer.innerHTML = `
      <h3>Servicios Seleccionados</h3>
      ${htmlServicios}
    `;
  }
});