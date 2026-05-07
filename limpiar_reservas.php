<?php
// Este script inactiva reservas que tengan más de 1 día y libera los libros reservados

$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

// Buscar reservas activas con más de 1 día
$query = "
    SELECT id_reserva, id_libro 
    FROM reservas 
    WHERE estado = 'activa' 
    AND fecha_reserva < NOW() - INTERVAL '1 day'
";

$resultado = pg_query($conexion, $query);

while ($reserva = pg_fetch_assoc($resultado)) {
    $id_reserva = $reserva['id_reserva'];
    $id_libro = $reserva['id_libro'];

    // Marcar reserva como inactiva
    pg_query($conexion, "UPDATE reservas SET estado = 'inactiva' WHERE id_reserva = $id_reserva");
  // Poner el libro nuevamente como disponible (solo si no está prestado)
    $estado_libro = pg_query($conexion, "SELECT estado FROM libros WHERE id_libro = $id_libro");
    $libro = pg_fetch_assoc($estado_libro);

    if ($libro && $libro['estado'] = 'reservado') {
        pg_query($conexion, "UPDATE libros SET estado = 'disponible' WHERE id_libro = $id_libro");
    }
}

pg_close($conexion);

echo "✅ Reservas caducadas limpiadas correctamente.";
?>
