<?php
header("Content-Type: application/json");
include "Conexion.php";

$input = json_decode(file_get_contents("php://input"), true);
$ids = $input["ids"] ?? [];

if (empty($ids)) {
  echo json_encode([]);
  exit;
}

try {
  $db = new Conexion();
  $conn = Conexion::obtenerConexion();

  // Preparar placeholders dinámicos (?, ?, ?, ...)
  $placeholders = implode(',', array_fill(0, count($ids), '?'));

  $stmt = $conn->prepare("SELECT idServicios, nombre, descripcion, precio, tiempo, imagen FROM servicios WHERE idServicios IN ($placeholders)");
  $stmt->execute($ids);
  $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Convertir imagen BLOB a base64 para mostrarla
  foreach ($servicios as &$servicio) {
    $servicio["imagen"] = "data:image/jpeg;base64," . base64_encode($servicio["imagen"]);
  }

  echo json_encode($servicios);
} catch (PDOException $e) {
  echo json_encode(["error" => "Error en el servidor"]);
}
