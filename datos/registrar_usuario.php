<?php
include "Conexion.php";
$db = new Conexion();
$conn = $db->obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir datos del formulario
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $genero = $_POST["genero"];
    $usuario = $_POST["usuario"];
    $contrasenia = hash("sha256", $_POST["contrasenia"]);

    try {
        $stmt = $conn->prepare("
            INSERT INTO usuario 
            (Usuario, contrasenia, nombre, apellido_paterno, apellido_materno, correo_electronico, telefono, fecha_nacimiento, genero)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $usuario,
            $contrasenia,
            $nombre,
            $apellido_paterno,
            $apellido_materno,
            $correo,
            $telefono,
            $fecha_nacimiento,
            $genero
        ]);

        echo json_encode(["success" => true, "mensaje" => "Usuario registrado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "mensaje" => "Error: " . $e->getMessage()]);
    }
}
?>
