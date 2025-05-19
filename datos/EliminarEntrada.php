<?php
header("Content-Type: application/json");
include "Conexion.php";

$db = new Conexion();
$conn = $db->obtenerConexion();

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? null;

if (!$id) {
  echo json_encode(["success" => false, "mensaje" => "ID no proporcionado"]);
  exit;
}

$sql = "DELETE FROM entradas WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt->execute([$id])) {
  echo json_encode(["success" => true, "mensaje" => "Entrada eliminada correctamente"]);
} else {
  echo json_encode(["success" => false, "mensaje" => "Error al eliminar entrada"]);
}
