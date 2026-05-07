<?php
session_start();

// Verificar que el usuario sea lector
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    echo "Acceso denegado.";
    exit;
}

// Conectar a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

$id_usuario = $_SESSION['id'];

if (isset($_POST['id_reserva'])) {
    // Buscar la última reserva activa
    $query = "SELECT id_reserva, id_libro FROM reservas 
              WHERE id_usuario = $1 AND estado = 'activa' 
              ORDER BY fecha_reserva DESC LIMIT 1";
    $resultado = pg_query_params($conexion, $query, [$id_usuario]);

    if ($resultado && pg_num_rows($resultado) > 0) {
        $fila = pg_fetch_assoc($resultado);
        $id_reserva = $fila['id_reserva'];
        $id_libro = $fila['id_libro'];
        // Cancelar solo esa reserva
        $query_cancelar = "UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = $1";
        $res_cancelar = pg_query_params($conexion, $query_cancelar, [$id_reserva]);
        

         // devolver el estado del libro
        $query_libro = "UPDATE libros SET estado = 'disponible' WHERE id_libro = $1";
        $res_libro = pg_query_params($conexion, $query_libro, [$id_libro]);
        

        if ($res_cancelar) {
            header("Location: mis_reservas.php?msg=✅ Última reserva cancelada con éxito");
            exit;
        } else {
            echo "❌ Error al cancelar: " . pg_last_error($conexion);
        }
    } else {
        header("Location: mis_reservas.php?msg=⚠️ No tienes reservas activas");
        exit;
    }
} else {
    echo "Petición inválida.";
}
