<?php
include "Conexion.php";
$db = new Conexion();
$conn = $db->obtenerConexion();

$titulo = $_POST['titulo'];
$resumen = $_POST['resumen'];
$contenido = $_POST['contenido'];
$imagen = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imagenTmp = $_FILES['imagen']['tmp_name'];
    $imagen = file_get_contents($imagenTmp);
}

$sql = "INSERT INTO entradas (titulo, resumen, contenido, imagen) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $titulo);
$stmt->bindParam(2, $resumen);
$stmt->bindParam(3, $contenido);
$stmt->bindParam(4, $imagen, PDO::PARAM_LOB);

if ($stmt->execute()) {
    echo "<script>alert('Entrada agregada correctamente'); window.location.href='../adminBlog.html';</script>";
} else {
    echo "<script>alert('Error al guardar la entrada'); history.back();</script>";
}
?>
