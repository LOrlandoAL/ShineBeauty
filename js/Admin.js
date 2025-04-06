document.addEventListener('DOMContentLoaded', function () {
    cargarServicios();

    function cargarServicios() {
        fetch('datos/TraerServicios.php') // <- Ajusta si quieres que traiga todos los servicios
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // 👈 Para que veas qué llega
                mostrarServicios(data); // ✅ Ya no dará error
            })
            .catch(error => console.error('Error al cargar servicios:', error));
    }

    function mostrarServicios(servicios) {
        const contenedor = document.getElementById('contenedor-servicios');
        contenedor.innerHTML = '';

        servicios.forEach(servicio => {
            const card = document.createElement('div');
            card.classList.add('servicio');

            const img = document.createElement('img');
            img.src = servicio.imagen;
            img.alt = servicio.nombre;

            const info = document.createElement('div');
            info.classList.add('servicio-info');
            info.innerHTML = `
                <h2>${servicio.nombre}</h2>
                <p><strong>Precio:</strong> $${servicio.precio}</p>
                <p><strong>Tiempo:</strong> ${servicio.tiempo} Hr</p>
                <p>${servicio.Servicioscol}</p>
            `;

            // Botón de editar
            const btnEditar = document.createElement('button');
            btnEditar.textContent = 'Editar';
            btnEditar.addEventListener('click', function () {
                editarServicio(servicio);
            });

            // Botón de eliminar
            const btnEliminar = document.createElement('button');
            btnEliminar.textContent = 'Eliminar';
            btnEliminar.addEventListener('click', function () {
                eliminarServicio(servicio);
            });

            card.appendChild(img);
            card.appendChild(info);
            card.appendChild(btnEditar);
            card.appendChild(btnEliminar);

            contenedor.appendChild(card);
        });
    }

    function editarServicio(servicio) {
        // Rellenamos el formulario con la info del servicio
        document.getElementById('tipo').value = servicio.tipo;
        document.getElementById('nombre').value = servicio.nombre;
        document.getElementById('descripcion').value = servicio.Servicioscol;
        document.getElementById('precio').value = servicio.precio;
        document.getElementById('tiempo').value = servicio.tiempo;

        // Cambiamos el action del formulario para editar
        const form = document.querySelector('.form');
        console.log('Editando servicio ID:', servicio.idServicios);
        form.action = 'datos/EditarServicio.php?id=' + servicio.idServicios;
    }

    function eliminarServicio(servicio) {
        if (confirm(`¿Estás seguro de eliminar el servicio "${servicio.nombre}"?`)) {
            fetch('datos/EditarServicio.php?id=' + servicio.id, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje); // Muestra el mensaje que viene del PHP
            
                if (data.redirect) {
                    window.location.href = data.redirect; // 🔥 Aquí haces la redirección
                }
            })
            .catch(error => console.error('Error:', error));
            
        }
    }
});

