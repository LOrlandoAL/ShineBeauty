<?php
header("Content-Type: application/json");
require_once "Conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $contrasenia = $_POST["contrasenia"];

    try {
        $db = new Conexion();
        $conn = Conexion::obtenerConexion();

        $stmt = $conn->prepare("SELECT idUsuario, Usuario, nombre, contrasenia FROM usuario WHERE Usuario = ?");
        $stmt->execute([$usuario]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && hash('sha256', $contrasenia) === $row['contrasenia']) {
            echo json_encode([
                "success" => true,
                "mensaje" => "Inicio de sesión exitoso",
                "usuario" => [
                    "nombre" => $row["nombre"],
                    "Usuario" => $row["Usuario"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "mensaje" => "Credenciales incorrectas"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "mensaje" => "Error en el servidor"]);
    }
}
