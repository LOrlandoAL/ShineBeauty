<?php
header("Content-Type: application/json");
include "Conexion.php";

$db = new Conexion();
$conn = $db->obtenerConexion();

$sql = "SELECT id, titulo, resumen, fecha, TO_BASE64(imagen) AS imagen FROM entradas ORDER BY fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
