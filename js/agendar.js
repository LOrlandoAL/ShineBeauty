document.addEventListener("DOMContentLoaded", function() {
  const usuario = JSON.parse(localStorage.getItem('usuario'));
  const fechaActual = new Date();
  const fechaMaxima = new Date();
  fechaMaxima.setDate(fechaActual.getDate() + 21);

  const bloqueCitas = document.getElementById('bloques-citas');
  const btnAgregarCita = document.querySelector('.btn-agregar-cita');

  if (usuario) {
    document.getElementById('nombre').value = usuario.Usuario;
    // Si tienes más campos en la tabla usuario, los puedes mapear aquí
  }

  // Cargar servicios una sola vez para reutilizar
  let serviciosDisponibles = [];

  fetch("datos/obtenerServicios.php")
      .then(response => response.json())
      .then(data => {
          serviciosDisponibles = data;
          agregarBloqueCita(); // Agrega el primer bloque al cargar
      });

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

  function generarOpcionesHora() {
      let opciones = '';
      for (let h = 6; h <= 18; h++) {
          const hora = `${h.toString().padStart(2, '0')}:00`;
          opciones += `<option value="${hora}">${hora}</option>`;
      }
      return opciones;
  }
});
