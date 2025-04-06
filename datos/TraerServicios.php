<?php
header('Content-Type: application/json');

// Mostrar errores para depuración (puedes quitarlo en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir archivo de conexión
require_once 'Conexion.php';

try {
    // Crear instancia y obtener conexión
    $conexionDB = new Conexion();
    $pdo = Conexion::obtenerConexion();

    if (!$pdo) {
        throw new Exception("No se pudo obtener conexión a la base de datos.");
    }

    // Preparar y ejecutar la consulta para obtener todos los servicios
    $stmt = $pdo->prepare("SELECT idServicios, tipo, nombre, Servicioscol, precio, tiempo, imagen FROM servicios");
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar resultados para incluir la ruta de la imagen
    foreach ($resultados as &$fila) {
        if (!empty($fila['imagen'])) {
            // Asumiendo que almacenas la ruta de la imagen en la BD
            $fila['imagen'] = 'datos/' . $fila['imagen'];
        } else {
            $fila['imagen'] = 'datos/no-image.png';
        }
    }

    echo json_encode($resultados);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
