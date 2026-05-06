<?php
session_start();

// Verificación de sesión
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    echo "Acceso denegado.";
    exit;
}

$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

if (isset($_POST['id_libro'])) {
    $id_libro = $_POST['id_libro'];
    $id_usuario = $_SESSION['id'];

    // Insertar reserva
    $query = "INSERT INTO reservas (id_libro, id_usuario, fecha_reserva, estado)
              VALUES ($1, $2, NOW(), 'pendiente')";
    $resultado = pg_query_params($conexion, $query, [$id_libro, $id_usuario]);

    if ($resultado) {
        header("Location: mis_reservas.php");
        exit;
    } else {
        echo "Error al reservar el libro.";
    }
} else {
    echo "Datos inválidos.";
}
