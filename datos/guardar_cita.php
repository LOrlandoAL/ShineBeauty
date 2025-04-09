<?php
$conexion = new mysqli("localhost", "root", "root", "sunshie");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$citas = $_POST['citas'];

// Simulación de usuario activo (Admin, producción usar $_SESSION)
$idUsuario = 1;

// Preparar inserción de cita
$stmtCita = $conexion->prepare("INSERT INTO citas (idUsuario, fecha, hora, nombreCliente, telefonoCliente, correoCliente) VALUES (?, ?, ?, ?, ?, ?)");
$stmtCita->bind_param("isssss", $idUsuario, $fecha, $hora, $nombre, $telefono, $correo);

// Preparar inserción de servicios
$stmtServicio = $conexion->prepare("INSERT INTO cita_servicios (idCita, idServicio, hora) VALUES (?, ?, ?)");

foreach ($citas as $cita) {
    $fecha = $cita['fecha'];
    $hora = $cita['hora'];

    // Validar capacidad antes de insertar
    $check = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE fecha = ? AND hora = ?");
    $check->bind_param("ss", $fecha, $hora);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] >= 5) {
        echo "Ya hay 5 citas para la fecha $fecha a la hora $hora. La cita no se guardó.<br>";
        continue;
    }

    // Guardar la cita
    $stmtCita->execute();
    $idCita = $stmtCita->insert_id;

    // Guardar los servicios seleccionados para esta cita
    foreach ($cita['servicios'] as $idServicio) {
        $stmtServicio->bind_param("iis", $idCita, $idServicio, $hora);
        $stmtServicio->execute();
    }

    echo "Cita agendada para el $fecha a las $hora.<br>";
}

$conexion->close();
?>
