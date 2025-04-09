document.addEventListener('DOMContentLoaded', function () {
    cargarServicios();

    function cargarServicios() {
        fetch('datos/TraerServicios.php')
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // Debug
                mostrarServicios(data);
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
                <p>${servicio.descripcion}</p>
            `;

            const btnEditar = document.createElement('button');
            btnEditar.textContent = 'Editar';
            btnEditar.addEventListener('click', function () {
                editarServicio(servicio);
            });

            const btnEliminar = document.createElement('button');
            btnEliminar.textContent = 'Eliminar';
            btnEliminar.addEventListener('click', function () {
                eliminarServicio(servicio.idServicios);
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
        document.getElementById('tipo').value = servicio.Tipo;
        document.getElementById('nombre').value = servicio.nombre;
        document.getElementById('descripcion').value = servicio.descripcion;
        document.getElementById('precio').value = servicio.precio;
        document.getElementById('tiempo').value = servicio.tiempo;

        const form = document.querySelector('.form');
        console.log('Editando servicio ID:', servicio.idServicios);
        form.action = 'datos/EditarServicio.php?id=' + servicio.idServicios;
    }

    function eliminarServicio(idServicio) {
        if (confirm('¿Estás seguro de eliminar este servicio?')) {
            fetch('datos/EliminarServicio.php?id=' + idServicio, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                cargarServicios(); // Recarga la lista
            })
            .catch(error => console.error('Error:', error));
        }
    }
});
