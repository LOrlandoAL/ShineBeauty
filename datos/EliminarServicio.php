<?php
include "Conexion.php";

if (isset($_POST['idServicios'])) {
    $id = $_POST['idServicios'];

    $db = new Conexion();
    $conn = $db->obtenerConexion();

    $sql = "DELETE FROM servicios WHERE idServicios = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['mensaje' => 'Servicio eliminado correctamente.']);
    } else {
        echo json_encode(['mensaje' => 'Error al eliminar el servicio.']);
    }
} else {
    echo json_encode(['mensaje' => 'ID de servicio no proporcionado.']);
}
?>
