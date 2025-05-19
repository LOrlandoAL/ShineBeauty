<?php
header('Content-Type: application/json');
include "Conexion.php";

$entrada = json_decode(file_get_contents("php://input"), true);
$id = $entrada["id"] ?? null;

if (!$id) {
  echo json_encode(["error" => "ID no proporcionado"]);
  exit;
}

$db = new Conexion();
$conn = $db->obtenerConexion();

$stmt = $conn->prepare("SELECT titulo, resumen, contenido, fecha, imagen FROM entradas WHERE id = ?");
$stmt->execute([$id]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
  if ($data['imagen']) {
    $data['imagen'] = base64_encode($data['imagen']);
  }
  echo json_encode($data);
} else {
  echo json_encode(["error" => "Entrada no encontrada"]);
}
?>
