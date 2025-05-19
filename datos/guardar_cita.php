<?php
$conexion = new mysqli("localhost", "root", "root", "sunshie");
if ($conexion->connect_error) {
    echo "<script>
        alert('❌ Error de conexión a la base de datos');
        window.history.back();
    </script>";
    exit();
}

$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';
$idsServicio = $_POST['idServicio'] ?? [];
$fechas = $_POST['fecha'] ?? [];
$horas = $_POST['hora'] ?? [];

$idUsuario = 1; // En producción usar sesión de usuario logueado

$stmtCita = $conexion->prepare("INSERT INTO citas (idUsuario, fecha, hora, nombreCliente, telefonoCliente, correoCliente) VALUES (?, ?, ?, ?, ?, ?)");
$stmtServicio = $conexion->prepare("INSERT INTO cita_servicios (idCita, idServicio, hora) VALUES (?, ?, ?)");

$exitos = 0;
$errores = [];

for ($i = 0; $i < count($idsServicio); $i++) {
    $idServicio = $idsServicio[$i];
    $fecha = $fechas[$i] ?? '';
    $hora = $horas[$i] ?? '';

    if (empty($fecha) || empty($hora)) {
        $errores[] = "Faltan datos para el servicio con ID $idServicio.";
        continue;
    }

    // Validar capacidad
    $check = $conexion->prepare("SELECT COUNT(*) as total FROM citas WHERE fecha = ? AND hora = ?");
    if (!$check) {
        $errores[] = "Error preparando validación de capacidad: " . $conexion->error;
        continue;
    }

    $check->bind_param("ss", $fecha, $hora);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result && $result['total'] >= 5) {
        $errores[] = "⚠️ No disponible: $fecha a las $hora (máximo 5 citas)";
        continue;
    }

    // Insertar cita
    if (!$stmtCita) {
        $errores[] = "Error preparando cita: " . $conexion->error;
        continue;
    }

    $stmtCita->bind_param("isssss", $idUsuario, $fecha, $hora, $nombre, $telefono, $correo);
    if (!$stmtCita->execute()) {
        $errores[] = "Error al ejecutar cita ($fecha $hora): " . $stmtCita->error;
        continue;
    }

    $idCita = $stmtCita->insert_id;

    // Insertar servicio
    if (!$stmtServicio) {
        $errores[] = "Error preparando servicio: " . $conexion->error;
        continue;
    }

    $stmtServicio->bind_param("iis", $idCita, $idServicio, $hora);
    if (!$stmtServicio->execute()) {
        $errores[] = "Error al registrar servicio $idServicio para la cita: " . $stmtServicio->error;
        continue;
    }

    $exitos++;
}


$conexion->close();

if ($exitos > 0) {
    echo "<script>
        alert('✅ Se agendaron $exitos cita(s) correctamente.');
        localStorage.removeItem('serviciosAgenda'); // Limpia servicios si se usa en HTML
        window.location.href = '../index.html';
    </script>";
} else {
    $mensaje = implode('\\n', $errores);
    echo "<script>
        alert('❌ No se pudo agendar ninguna cita.\\n$mensaje');
        window.history.back();
    </script>";
}
?>
