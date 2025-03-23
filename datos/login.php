<?php
include "Conexion.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = hash('sha256', $_POST['contrasenia']); 

    $db = new Conexion();
    $conn = $db->obtenerConexion();
    $stmt = $conn->prepare("SELECT idUsuario FROM usuario WHERE usuario = :usuario AND contrasenia = :contrasenia");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->bindParam(":contrasenia", $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        header("Location: admin.php"); // Redirigir a la página de administrador
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='../inicioUs.html';</script>";
    }
}
?>
