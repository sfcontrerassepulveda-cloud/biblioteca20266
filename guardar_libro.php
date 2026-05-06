<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

$titulo     = $_POST['titulo'];
$autor      = $_POST['autor'];
$año        = $_POST['año'];
$categoria  = $_POST['categoria'];
$isbn       = $_POST['isbn'];
$piso       = $_POST['piso'];
$estante    = $_POST['estante'];
$columna    = $_POST['columna'];
$fila       = $_POST['fila'];

$ubicacion = "Piso $piso - Estante $estante - Columna $columna - Fila $fila";

$query = "INSERT INTO libros (titulo, autor, año, categoria, isbn, ubicacion)
          VALUES ('$titulo', '$autor', $año, '$categoria', '$isbn', '$ubicacion')";

$resultado = pg_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Libro</title>
    <style>
        body {
            background: linear-gradient(to right, #8EC5FC, #E0C3FC); /* ✅ Mantengo tu fondo degradado */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .contenedor {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 90%;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #444;
        }

        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #6C63FF;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        a:hover {
            background-color: #574fd6;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <?php if ($resultado): ?>
            <h1>✅ Libro registrado correctamente.</h1>
            <p><strong>Ubicación:</strong> <?php echo $ubicacion; ?></p>
        <?php else: ?>
            <h1>❌ Error al registrar el libro.</h1>
        <?php endif; ?>

        <a href="registrar_libro.php">Registrar otro libro</a>
        <a href="./ver_libros.php">Ver lista de libros</a>
        <a href="./inventario.php">Ir a Inventarios</a>
    </div>
</body>
</html>
