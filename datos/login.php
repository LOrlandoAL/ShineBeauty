<?php
header("Content-Type: application/json");
include "Conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $password = hash('sha256', $_POST['contrasenia']); 

    try {
        $db = new Conexion();
        $conn = $db->obtenerConexion();

        // 🔄 Se incluyen también correo_electronico y telefono
        $stmt = $conn->prepare("SELECT idUsuario, Usuario, nombre, tipo, correo_electronico, telefono FROM usuario WHERE Usuario = :usuario AND contrasenia = :contrasenia");
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":contrasenia", $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "success" => true,
                "mensaje" => "Inicio de sesión exitoso",
                "usuario" => [
                    "id" => $user["idUsuario"],
                    "nombre" => $user["nombre"],
                    "Usuario" => $user["Usuario"],
                    "tipo" => $user["tipo"],
                    "correo_electronico" => $user["correo_electronico"],
                    "telefono" => $user["telefono"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "mensaje" => "Usuario o contraseña incorrectos"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "mensaje" => "Error en el servidor"]);
    }
}
?>
