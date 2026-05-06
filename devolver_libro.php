<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

if (!isset($_POST['id_prestamo'])) {
    echo "ID de préstamo no proporcionado.";
    exit;
}

$id_prestamo = (int)$_POST['id_prestamo'];

// 🔹 Capturar observación desde el formulario
$observacion = isset($_POST['observacion']) ? $_POST['observacion'] : null;

$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

// Obtener detalles del préstamo y usuario
$res = pg_query($conexion, "
    SELECT p.id_libro, p.id_usuario, p.fecha_devolucion 
    FROM prestamos p 
    WHERE p.id_prestamo = $id_prestamo
");

if (!$res || pg_num_rows($res) == 0) {
    $mensaje = "Préstamo no encontrado.";
    $estado = "error";
} else {
    $datos = pg_fetch_assoc($res);
    $id_libro = $datos['id_libro'];
    $id_usuario = $datos['id_usuario'];
    $fecha_devolucion = $datos['fecha_devolucion'];

    // Fecha real (actual)
    $fecha_real = date("Y-m-d");
    $hay_penalidad = false;
    if ($fecha_real > $fecha_devolucion) {
        $hay_penalidad = true;
        $fecha_inicio_penalidad = $fecha_real;
        $fecha_final_penalidad = date("Y-m-d", strtotime("$fecha_real +3 days"));
    }

    // 🔹 Actualizar préstamo con observación incluida
    pg_query_params($conexion, "
        UPDATE prestamos 
        SET estado = 'finalizado', 
            fecha_real_devolucion = $1, 
            penalizado = $2,
            observaciones = $3
        WHERE id_prestamo = $4
    ", [$fecha_real, $hay_penalidad ? 'TRUE' : 'FALSE', $observacion, $id_prestamo]);

    // Actualizar estado del libro
    pg_query($conexion, "
        UPDATE libros 
        SET estado = 'disponible' 
        WHERE id_libro = $id_libro
    ");
    // Actualizar estado de la reserva
    pg_query_params($conexion, "
        UPDATE reservas
        SET estado = 'finalizado'
        WHERE  id_usuario = $1 and id_libro =$2 and estado = 'aceptada' 
    ", [$id_usuario, $id_libro]);
    // Registrar penalidad si aplica
    if ($hay_penalidad) {
        pg_query($conexion, "
            INSERT INTO penalidades (id_usuario, fecha_inicio, fecha_final)
            VALUES ($id_usuario, '$fecha_inicio_penalidad', '$fecha_final_penalidad')
        ");
    }

    // Mensaje de confirmación
    $mensaje = "✅ Libro marcado como devuelto.";
    if ($observacion) {
        $mensaje .= "<br>📌 Observación: <strong>$observacion</strong>.";
    }
    if ($hay_penalidad) {
        $mensaje .= "<br>⚠ Penalidad aplicada del <strong>$fecha_inicio_penalidad</strong> al <strong>$fecha_final_penalidad</strong>.";
    }
    $estado = "exito";
}

pg_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado de Devolución</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .card {
        background: white;
        border-radius: 15px;
        padding: 30px 40px;
        max-width: 450px;
        text-align: center;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.2);
        animation: aparecer 0.4s ease-in-out;
    }
    @keyframes aparecer {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
.icon {
        font-size: 50px;
        margin-bottom: 15px;
    }
    .exito { color: #2ecc71; }
    .error { color: #e74c3c; }
    h2 { margin-bottom: 15px; }
    p { font-size: 16px; color: #555; }
    .btn {
        display: inline-block;
        margin-top: 20px;
        background-color: #4A90E2;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn:hover {
        background-color: #357ABD;
        transform: translateY(-2px);
    }
</style>
</head>
<body>
    <div class="card">
        <div class="icon <?php echo $estado; ?>">
            <?php echo ($estado == 'exito') ? '✅' : '❌'; ?>
        </div>
        <h2><?php echo ($estado == 'exito') ? 'Operación Exitosa' : 'Error en la Operación'; ?></h2>
        <p><?php echo $mensaje; ?></p>
        <a href="panel_prestamo.php" class="btn">🔙 Volver al Panel</a>
    </div>
</body>
</html>