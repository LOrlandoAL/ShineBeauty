<?php
$conexion = new mysqli("localhost", "root", "root", "sunshie");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT idServicios, nombre FROM servicios";
$result = $conexion->query($sql);

$servicios = [];
while ($row = $result->fetch_assoc()) {
    $servicios[] = $row;
}

echo json_encode($servicios);
$conexion->close();
?>
