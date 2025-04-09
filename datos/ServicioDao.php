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

// Verificar que se haya subido la imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imagenTemp = $_FILES['imagen']['tmp_name'];
    $imagenBinaria = file_get_contents($imagenTemp); // Leer la imagen como binario

    // Preparar la consulta
    $sql = "INSERT INTO servicios (Tipo, nombre, descripcion, precio, tiempo, imagen) 
            VALUES (:tipo, :nombre, :descripcion, :precio, :tiempo, :imagen)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':tiempo', $tiempo);
    $stmt->bindParam(':imagen', $imagenBinaria, PDO::PARAM_LOB);

    if ($stmt->execute()) {
        echo "Servicio agregado correctamente.";
    } else {
        echo "Error al agregar servicio.";
    }
} else {
    echo "Por favor selecciona una imagen para el servicio.";
}

// Cerrar la conexión
$conn = null;
?>
