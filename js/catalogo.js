document.addEventListener('DOMContentLoaded', () => {
  const serviciosLista = document.getElementById('servicios-lista');

  fetch('datos/TraerServicios.php')
    .then(response => response.json())
    .then(servicios => {
      const serviciosFiltrados = servicios.filter(servicio => servicio.Tipo.toLowerCase() === categoriaActual.toLowerCase());

      serviciosFiltrados.forEach(servicio => {
        const card = document.createElement('div');
        card.classList.add('servicio-card');

        card.innerHTML = `
          <img src="${servicio.imagen}" alt="${servicio.nombre}" class="servicio-imagen">
          <div class="servicio-info">
            <h3>${servicio.nombre}</h3>
            <div class="detalle-icono">
              <img src="img/icons/dollar.svg" alt="Precio">
              <span>Precio: $${servicio.precio} MXN</span>
            </div>
            <div class="detalle-icono">
              <img src="img/icons/clock.svg" alt="Tiempo">
              <span>Tiempo: ${servicio.tiempo}h aprox</span>
            </div>
            <p class="descripcion">${servicio.Servicioscol}</p>
            <button onclick="agregarAServiciosAgenda(ID_DEL_SERVICIO)">Agregar a Agenda</button>
          </div>
        `;

        serviciosLista.appendChild(card);
      });
    })
    .catch(error => {
      console.error('Error al cargar los servicios:', error);
    });
});
