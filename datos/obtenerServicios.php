<?php
header('Content-Type: application/json');
require_once "Conexion.php";

try {
    $db = new Conexion();
    $conn = Conexion::obtenerConexion();

    $stmt = $conn->prepare("SELECT idServicios, nombre, descripcion, precio, tiempo, tipo, imagen FROM servicios");
    $stmt->execute();
    $result = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['imagen'] = 'data:image/jpeg;base64,' . base64_encode($row['imagen']);
        $result[] = $row;
    }

    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener servicios"]);
}
?>
