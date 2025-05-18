<?php
header('Content-Type: application/json');
require_once 'Conexion.php';

$tipo = $_GET['tipo'] ?? '';

if (empty($tipo)) {
    echo json_encode(["error" => "Tipo de servicio no especificado"]);
    exit;
}

try {
    $db = new Conexion();
    $conn = Conexion::obtenerConexion();

    $stmt = $conn->prepare("SELECT idServicios, nombre, precio, tiempo, descripcion, imagen FROM servicios WHERE LOWER(Tipo) = LOWER(?)");
    $stmt->execute([$tipo]);

    $servicios = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['imagen'] = '/ShineBeauty/datos/uploads/' . basename($row['imagen']); // Ruta pública si las guardas en /uploads
        $servicios[] = $row;
    }

    // Ensure JSON encoding works
    $jsonOutput = json_encode($servicios);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "JSON encoding failed"]);
        exit;
    }

    echo $jsonOutput;
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error", "message" => $e->getMessage()]);
}
?>