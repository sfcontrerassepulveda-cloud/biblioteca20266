<?php
session_start();

// Solo bibliotecarios pueden eliminar
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

// Conexión a la base de datos
$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

// Comprobar que llegó el id
if (isset($_POST['id_libro']) && is_numeric($_POST['id_libro'])) {
    $id_libro = intval($_POST['id_libro']);

    // Eliminar libro
    $query = "update libros set eliminado='si' WHERE id_libro = $1";
    $resultado = pg_query_params($conexion, $query, [$id_libro]);

    if ($resultado) {
        header("Location: ver_libros.php?mensaje=eliminado");
        exit;
    } else {
        echo "Error al eliminar el libro.";
    }
} else {
    echo "ID no válido.";
}
?>
