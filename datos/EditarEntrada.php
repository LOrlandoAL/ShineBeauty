<?php
include "Conexion.php";
$db = new Conexion();
$conn = $db->obtenerConexion();

$id = $_POST["id"] ?? null;
$titulo = $_POST["titulo"] ?? "";
$resumen = $_POST["resumen"] ?? "";
$contenido = $_POST["contenido"] ?? "";

if (!$id) {
  echo "ID de entrada no proporcionado.";
  exit;
}

// Si hay imagen nueva, actualizarla también
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
  $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);
  $sql = "UPDATE entradas SET titulo = ?, resumen = ?, contenido = ?, imagen = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$titulo, $resumen, $contenido, $imagen, $id]);
} else {
  $sql = "UPDATE entradas SET titulo = ?, resumen = ?, contenido = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$titulo, $resumen, $contenido, $id]);
}

header("Location: ../adminEntradas.html");
exit;
