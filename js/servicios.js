// Función para generar elementos HTML con los datos
function generarElementosHTML(datos) {
    const contenedor = document.getElementById('contenedor-datos');
    contenedor.innerHTML = ''; // Limpiar antes de insertar

    if (datos.length === 0) {
        contenedor.innerHTML = '<p>No se encontraron servicios de este tipo.</p>';
        return;
    }

    datos.forEach(item => {
        const card = document.createElement('div');
        card.classList.add('servicio'); // Misma clase que el manual
    
        // Crear la imagen
        if (item.imagen) {
            const img = document.createElement('img');
            img.src = item.imagen;
            img.alt = item.nombre;
            card.appendChild(img);
        }
    
        // Crear el contenedor de la info
        const servicioInfo = document.createElement('div');
        servicioInfo.classList.add('servicio-info');
    
        // Crear título
        const titulo = document.createElement('h2');
        titulo.textContent = item.nombre;
    
        // Crear precio
        const precio = document.createElement('p');
        precio.innerHTML = `<strong>💰 Precio:</strong> $${item.precio}`;
    
        // Crear tiempo
        const tiempo = document.createElement('p');
        tiempo.innerHTML = `<strong>⏳ Tiempo:</strong> ${item.tiempo} Hr`;
    
        // Crear descripción
        const descripcion = document.createElement('p');
        descripcion.textContent = item.Servicioscol;
    
        // Agregar elementos al contenedor de info
        servicioInfo.appendChild(titulo);
        servicioInfo.appendChild(precio);
        servicioInfo.appendChild(tiempo);
        servicioInfo.appendChild(descripcion);
    
        // Agregar contenedor de info a la tarjeta
        card.appendChild(servicioInfo);
    
        // Finalmente agregar la tarjeta al contenedor principal
        contenedor.appendChild(card);
    });
    
}

// Función para cargar los datos desde PHP
function cargarServiciosPorTipo(tipo) {
    fetch(`datos/obteberServicios.php?tipo=${encodeURIComponent(tipo)}`)
        .then(res => res.json())
        .then(data => generarElementosHTML(data))
        .catch(error => {
            console.error('Error al cargar los servicios:', error);
        });
}

// Llama a la función cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const tipo = 'Peinado'; // Puedes cambiar este valor según el archivo
    cargarServiciosPorTipo(tipo);
});
