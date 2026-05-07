<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    echo "Acceso denegado. Solo lectores pueden reservar libros.";
    exit;
}

$id_usuario = $_SESSION['id'];
$id_libro = $_POST['id_libro'];

$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

// ================== REGLAS DE NEGOCIO ==================

// 1. Verificar préstamos activos
$res_prestamos = pg_query($conexion, "
    SELECT COUNT(*) AS total 
    FROM prestamos 
    WHERE id_usuario = $id_usuario AND estado = 'activo'
");
$prestamos = pg_fetch_assoc($res_prestamos)['total'];

// 2. Verificar reservas activas
$res_reservas = pg_query($conexion, "
    SELECT COUNT(*) AS total 
    FROM reservas 
    WHERE id_usuario = $id_usuario AND estado = 'activa'
");
$reservas = pg_fetch_assoc($res_reservas)['total'];

// 3. Verificar penalidades activas (con fecha de finalización)
$res_penalidades = pg_query($conexion, "
    SELECT fecha_final
    FROM penalidades 
    WHERE id_usuario = $id_usuario 
      AND NOW() <= fecha_final
    ORDER BY fecha_final DESC 
    LIMIT 1
");
$penalidad = pg_fetch_assoc($res_penalidades);

// --- Aplicar restricciones ---
if ($prestamos >= 2) {
    $error = "❌ No puedes hacer reservas porque ya tienes 2 préstamos activos.";
} elseif ($prestamos >= 1 && $reservas >= 1) {
    $error = "❌ No puedes hacer reservas porque ya tienes 1 préstamo y 1 reserva activa.";
} elseif ($reservas >= 2) {
    $error = "❌ No puedes hacer reservas porque ya tienes 2 reservas activas.";
} elseif ($penalidad) {
    $fecha_final = date("d/m/Y", strtotime($penalidad['fecha_final']));
    $error = "⚠ No puedes hacer reservas porque tienes una penalidad activa hasta el <strong>$fecha_final</strong>.";
}

if (isset($error)) {
    echo "<div style='background:#ffe6e6; border:2px solid red; border-radius:15px; width:60%; margin:50px auto; padding:20px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2);'>
            <h2 style='color:red;'>$error</h2>
            <a href='ver_libros.php' style='display:inline-block; margin-top:15px; padding:10px 20px; background:#ff4d4d; color:white; text-decoration:none; border-radius:10px;'>🔙 Volver a la lista de libros</a>
          </div>";
    exit;
}

// ================== VALIDAR ESTADO DEL LIBRO ==================
$consulta_estado = pg_query($conexion, "SELECT estado FROM libros WHERE id_libro = $id_libro");
$libro = pg_fetch_assoc($consulta_estado);

if (!$libro || $libro['estado'] != 'disponible') {
    echo "<div style='background:#ffe6e6; border:2px solid red; border-radius:15px; width:60%; margin:50px auto; padding:20px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2);'>
            <h2 style='color:red;'>Lo sentimos, el libro no está disponible para reservar.</h2>
            <a href='ver_libros.php' style='display:inline-block; margin-top:15px; padding:10px 20px; background:#ff4d4d; color:white; text-decoration:none; border-radius:10px;'>🔙 Volver a la lista de libros</a>
          </div>";
    exit;
}

// ================== CREAR RESERVA ==================
// Insertar la reserva
pg_query($conexion, "
    INSERT INTO reservas (id_usuario, id_libro, fecha_reserva,fecha_expiracion, estado) 
    VALUES ($id_usuario, $id_libro, CURRENT_DATE,CURRENT_DATE + INTERVAL '1 day', 'activa')
");
// Cambiar el estado del libro a reservado
pg_query($conexion, "UPDATE libros SET estado = 'reservado' WHERE id_libro = $id_libro");

// ================== MENSAJE DE ÉXITO ==================
echo "<div style='background:#e6ffe6; border:2px solid green; border-radius:15px; width:70%; margin:50px auto; padding:25px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2); border-radius:20px;'>
        <h2 style='color:green;'>✅ Reserva activa registrada con éxito.</h2>
        <p style='font-size:16px; color:#333;'>Recuerda que dispones de un día, a partir de la fecha, para realizar el préstamo dentro del horario de atención de la biblioteca .<br>
      <a href='http://localhost/biblioteca/lector/' style='display:inline-block; margin-top:20px; padding:12px 25px; background:#4CAF50; color:white; text-decoration:none; font-weight:bold; border-radius:10px; box-shadow:0 3px 6px rgba(0,0,0,0.2); transition:0.3s;'>📚 Devolver</a>
      </div>";


pg_close($conexion);
?>

<!-- ====== Fondo de pantalla ====== -->
<style>
  body {
    background: linear-gradient(135deg, #c9d6ff, #e2e2e2);
    font-family: Arial, sans-serif;
  }
</style>
