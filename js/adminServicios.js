document.addEventListener("DOMContentLoaded", function () {
  const contenedorServicios = document.getElementById("servicios-categorias");

  function cargarServicios() {
    fetch("datos/TraerServicios.php")
      .then(response => response.json())
      .then(data => {
        mostrarServicios(data);
      })
      .catch(error => console.error("Error al cargar servicios:", error));
  }

  function mostrarServicios(servicios) {
    contenedorServicios.innerHTML = "";

    const listaServicios = document.createElement("div");
    listaServicios.classList.add("servicio-listado");

    servicios.forEach(servicio => {
      const card = document.createElement("div");
      card.classList.add("servicio-card");

      // Imagen del servicio
      const img = document.createElement("img");
      img.src = servicio.imagen;
      img.alt = servicio.nombre;

      // Información del servicio
      const info = document.createElement("div");
      info.classList.add("servicio-info");

      const nombre = document.createElement("h4");
      nombre.textContent = servicio.nombre;

      const precio = document.createElement("p");
      precio.innerHTML = `<strong>💰 Precio:</strong> ${servicio.precio} MXN`;

      const tiempo = document.createElement("p");
      tiempo.innerHTML = `<strong>⏳ Tiempo:</strong> ${servicio.tiempo} hr aprox`;

      const descripcion = document.createElement("p");
      descripcion.textContent = servicio.Servicioscol;

      info.appendChild(nombre);
      info.appendChild(precio);
      info.appendChild(tiempo);
      info.appendChild(descripcion);

      // Acciones (editar / eliminar)
      const acciones = document.createElement("div");
      acciones.classList.add("servicio-actions");

      const btnEditar = document.createElement("button");
      btnEditar.classList.add("btn-editar");
      btnEditar.textContent = "Editar";
      btnEditar.addEventListener("click", () => editarServicio(servicio));

      const btnEliminar = document.createElement("button");
      btnEliminar.classList.add("btn-eliminar");
      btnEliminar.textContent = "Eliminar";
      btnEliminar.addEventListener("click", () => eliminarServicio(servicio.idServicios));

      acciones.appendChild(btnEditar);
      acciones.appendChild(btnEliminar);

      // Montar la tarjeta
      card.appendChild(img);
      card.appendChild(info);
      card.appendChild(acciones);

      listaServicios.appendChild(card);
    });

    contenedorServicios.appendChild(listaServicios);
  }

  function eliminarServicio(idServicio) {
    if (confirm("¿Estás seguro de eliminar este servicio?")) {
      const formData = new FormData();
      formData.append("idServicios", idServicio);

      fetch("datos/EliminarServicio.php", {
        method: "POST",
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          alert(data.mensaje);
          cargarServicios();
        })
        .catch(error => console.error("Error al eliminar servicio:", error));
    }
  }

function editarServicio(servicio) {
  const tipoSelect = document.getElementById("tipo");
  const optionToSelect = Array.from(tipoSelect.options).find(option => 
  option.value && servicio.tipo && option.value.trim().toLowerCase() === servicio.tipo.trim().toLowerCase()
);

if (optionToSelect) {
  optionToSelect.selected = true;
} else {
  console.warn("⚠️ Tipo de servicio no encontrado en el select:", servicio.tipo);
}


    document.getElementById("nombre").value = servicio.nombre;
    document.getElementById("descripcion").value = servicio.descripcion;
    document.getElementById("precio").value = servicio.precio;
    document.getElementById("tiempo").value = servicio.tiempo;

    const form = document.querySelector("form");
    form.action = `datos/EditarServicio.php?id=${servicio.idServicios}`;

    form.scrollIntoView({ behavior: "smooth" });

    const submitBtn = document.querySelector("form button[type=submit]");
    submitBtn.textContent = "Actualizar Servicio";
    submitBtn.style.backgroundColor = "#ffaa00"; // opcional
    <button type="reset" onclick="location.reload()">Cancelar edición</button>
  }

  cargarServicios();
});
