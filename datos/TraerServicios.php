<?php
header('Content-Type: application/json');

include "Conexion.php";
$db = new Conexion();
$conn = $db->obtenerConexion();

$sql = "SELECT * FROM servicios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

$servicios = [];

foreach ($resultado as $servicio) {
    $servicio['imagen'] = 'data:image/jpeg;base64,' . base64_encode($servicio['imagen']);
    $servicios[] = $servicio;
}

echo json_encode($servicios);
?>
