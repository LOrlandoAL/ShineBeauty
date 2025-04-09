<?php
// Mostrar errores para depuración (puedes quitar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Incluir conexión
require_once 'Conexion.php';

// Verificar que venga el ID en la URL
if (!isset($_GET['id'])) {
    echo json_encode(['mensaje' => 'Error: Falta el ID del servicio.']);
    exit;
}

$id = $_GET['id'];

// Verificar que los datos del formulario estén completos
if (empty($_POST['tipo']) || empty($_POST['nombre']) || empty($_POST['descripcion']) || empty($_POST['precio']) || empty($_POST['tiempo'])) {
    echo json_encode(['mensaje' => 'Error: Todos los campos son obligatorios.']);
    exit;
}

$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$tiempo = $_POST['tiempo'];

// Conectarse a la base de datos
try {
    $conexionDB = new Conexion();
    $pdo = Conexion::obtenerConexion();

    // Verificamos si se envió una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = __DIR__ . '/../uploads/' . basename($nombreImagen);

        // Mover la imagen a la carpeta uploads
        if (move_uploaded_file($imagenTmp, $rutaDestino)) {
            // Actualizar todos los campos incluyendo la imagen
            $stmt = $pdo->prepare("UPDATE servicios SET tipo = :tipo, nombre = :nombre, Servicioscol = :descripcion, precio = :precio, tiempo = :tiempo, imagen = :imagen WHERE idServicios = :id");
            $stmt->bindParam(':imagen', $nombreImagen, PDO::PARAM_STR);
        } else {
            echo json_encode(['mensaje' => 'Error al subir la nueva imagen.']);
            exit;
        }
    } else {
        // Actualizar todos los campos excepto la imagen
        $stmt = $pdo->prepare("UPDATE servicios SET tipo = :tipo, nombre = :nombre, Servicioscol = :descripcion, precio = :precio, tiempo = :tiempo WHERE idServicios = :id");
    }

    // Bind de parámetros generales
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmt->bindParam(':tiempo', $tiempo, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode([
            'mensaje' => 'Servicio actualizado correctamente.',
            'redirect' => '../admin.html' // <--- Ruta a donde quieres redirigir
        ]);
    } else {
        echo json_encode(['mensaje' => 'Error al actualizar el servicio.']);
    }

} catch (Exception $e) {
    echo json_encode(['mensaje' => 'Error: ' . $e->getMessage()]);
}
