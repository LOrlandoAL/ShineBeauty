<?php
header('Content-Type: application/json');

// Mostrar errores para depuración (puedes quitar esto después)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir archivo de conexión
require_once 'Conexion.php';

// Verificar parámetro
if (!isset($_GET['tipo'])) {
    echo json_encode(['error' => 'Falta el parámetro tipo']);
    exit;
}

$tipo = $_GET['tipo'];

try {
    // Crear instancia y obtener conexión
    $conexionDB = new Conexion();
    $pdo = Conexion::obtenerConexion();

    if (!$pdo) {
        throw new Exception("No se pudo obtener conexión a la base de datos.");
    }

    // Preparar y ejecutar consulta
    $stmt = $pdo->prepare("SELECT tipo, nombre, Servicioscol, precio, tiempo, imagen FROM servicios WHERE tipo = :tipo");
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultados as &$fila) {
        if (!empty($fila['imagen'])) {
            // Si la imagen es una ruta de archivo, solo la concatenas
            $fila['imagen'] = 'datos/' . $fila['imagen'];
        } else {
            // Opcional: imagen por defecto si no hay
            $fila['imagen'] = 'uploads/no-image.png';
        }
    }

    echo json_encode($resultados);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
