<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

$id_reserva = $_POST['id_reserva'];
$id_libro = $_POST['id_libro'];

$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error de conexión.");
}

// Obtener el ID del usuario que hizo la reserva
$res = pg_query($conexion, "SELECT id_usuario FROM reservas WHERE id_reserva = $id_reserva");
$datos = pg_fetch_assoc($res);
if (!$datos) {
    $mensaje = "❌ No se encontró la reserva.";
    $estado = "error";
} else {
    $id_usuario = $datos['id_usuario'];

    // Calcular la fecha de devolución (1 días después)
    $fecha_devolucion = date('Y-m-d ', strtotime('+3 days'));
   
    // Registrar el préstamo directamente con PostgreSQL calculando la devolución
$insertar = pg_query($conexion, "
    INSERT INTO prestamos (id_usuario, id_libro, estado, fecha_prestamo, fecha_devolucion)
    VALUES ($id_usuario, $id_libro, 'activo', NOW(), NOW() + INTERVAL '3 days')
");


    if (!$insertar) {
        $mensaje = "❌ Error al registrar el préstamo.";
        $estado = "error";
    } else {
        // Cambiar estado del libro y la reserva
        pg_query($conexion, "UPDATE libros SET estado = 'prestado' WHERE id_libro = $id_libro");
        pg_query($conexion, "UPDATE reservas SET estado = 'aceptada', fecha_expiracion = NOW() + INTERVAL '1 day' WHERE id_reserva = $id_reserva");

        $mensaje = "✅ Préstamo registrado correctamente.<br>Fecha de devolución: <strong>$fecha_devolucion</strong>";
        $estado = "exito";
    }
}

pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado de Préstamo</title>
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
        <a href="panel_reservas.php" class="btn">🔙 Volver al Panel</a>
    </div>
</body>
</html>
