<?php
include "Conexion.php";
$db = new Conexion();
$conn = $db->obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_GET['id'];
    $tipo = $_POST['tipo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $tiempo = $_POST['tiempo'];

    // Verificar si se ha subido una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTemp = $_FILES['imagen']['tmp_name'];
        $imagenBinaria = file_get_contents($imagenTemp);

        $sql = "UPDATE servicios SET Tipo = :tipo, nombre = :nombre, descripcion = :descripcion, precio = :precio, tiempo = :tiempo, imagen = :imagen WHERE idServicios = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':imagen', $imagenBinaria, PDO::PARAM_LOB);
    } else {
        // No se subió imagen
        $sql = "UPDATE servicios SET Tipo = :tipo, nombre = :nombre, descripcion = :descripcion, precio = :precio, tiempo = :tiempo WHERE idServicios = :id";
        $stmt = $conn->prepare($sql);
    }

    // Parámetros comunes
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':tiempo', $tiempo);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Servicio actualizado correctamente'); window.location.href='../adminServicios.html';</script>";
    } else {
        echo "<script>alert('❌ Error al actualizar el servicio'); window.history.back();</script>";
    }
}
?>
