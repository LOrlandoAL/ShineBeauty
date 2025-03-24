<?php
include "Conexion.php";
$db = new Conexion();
$conn = $db->obtenerConexion();

// Recibir datos del formulario
$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$tiempo = $_POST['tiempo'];

// Verificar que la carpeta 'uploads' exista
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Manejo de la imagen
$imagenNombre = $_FILES['imagen']['name'];
$imagenTemp = $_FILES['imagen']['tmp_name'];
$rutaDestino = "uploads/" . basename($imagenNombre);

if (move_uploaded_file($imagenTemp, $rutaDestino)) {
    // Insertar datos en la BD
    $sql = "INSERT INTO servicios (tipo, nombre, Servicioscol, precio, tiempo, imagen) 
            VALUES (:tipo, :nombre, :descripcion, :precio, :tiempo, :imagen)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':tiempo', $tiempo);
    $stmt->bindParam(':imagen', $rutaDestino);

    if ($stmt->execute()) {
        echo "Servicio agregado correctamente.";
    } else {
        echo "Error al agregar servicio.";
    }
} else {
    echo "Error al subir la imagen.";
}

// Cerrar la conexión correctamente
$conn = null;
?>
