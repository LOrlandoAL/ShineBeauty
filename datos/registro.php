<?php
include "Conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $apellidoP = $_POST["apellido_paterno"];
    $apellidoM = $_POST["apellido_materno"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo_electronico"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $genero = $_POST["genero"];
    $contrasenia = hash("sha256", $_POST["contrasenia"]);
    $tipo = "cliente"; // Por defecto todos se registran como cliente

    try {
        $db = new Conexion();
        $conn = Conexion::obtenerConexion();

        // Verificar que el usuario o correo no exista previamente
        $verificar = $conn->prepare("SELECT * FROM usuario WHERE Usuario = :usuario OR correo_electronico = :correo");
        $verificar->bindParam(":usuario", $usuario);
        $verificar->bindParam(":correo", $correo);
        $verificar->execute();

        if ($verificar->rowCount() > 0) {
            echo "<script>alert('El usuario o correo ya está registrado.'); window.history.back();</script>";
            exit;
        }

        $sql = "INSERT INTO usuario (Usuario, contrasenia, nombre, apellido_paterno, apellido_materno, correo_electronico, telefono, fecha_nacimiento, genero, tipo)
                VALUES (:usuario, :contrasenia, :nombre, :apellidoP, :apellidoM, :correo, :telefono, :fecha_nacimiento, :genero, :tipo)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":contrasenia", $contrasenia);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidoP", $apellidoP);
        $stmt->bindParam(":apellidoM", $apellidoM);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":fecha_nacimiento", $fecha_nacimiento);
        $stmt->bindParam(":genero", $genero);
        $stmt->bindParam(":tipo", $tipo);

        $stmt->execute();

        echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href = '/login.html';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error en el registro: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
