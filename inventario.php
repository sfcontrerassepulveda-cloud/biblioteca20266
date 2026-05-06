<?php
session_start();

// Datos de conexión
$host = "localhost";
$usuario = "postgres";
$clave = "1234";
$bd = "biblioteca2025";
$puerto = "5432";

// Conexión con PostgreSQL
$conn = pg_connect("host=$host port=$puerto dbname=$bd user=$usuario password=$clave");
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

// Consultas
$sql_prestados = "SELECT COUNT(*) AS total FROM prestamos WHERE fecha_real_devolucion IS NULL";
$prestados = pg_fetch_assoc(pg_query($conn, $sql_prestados))['total'];

$sql_reservados = "SELECT COUNT(*) AS total FROM reservas WHERE fecha_expiracion >= CURRENT_DATE AND estado= 'activa'";
$reservados = pg_fetch_assoc(pg_query($conn, $sql_reservados))['total'];

$sql_total_libros = "SELECT COUNT(*) AS total FROM libros";
$total_libros = pg_fetch_assoc(pg_query($conn, $sql_total_libros))['total'];

// Calcular disponibles
//$disponibles = $total_libros - $prestados - $reservados;
//if ($disponibles < 0) $disponibles = 0;
$sql_total_libros_disponibles = "SELECT COUNT(*) AS total FROM libros Where estado = 'disponible'";
$disponibles = pg_fetch_assoc(pg_query($conn, $sql_total_libros_disponibles))['total'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Libros</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('./imagenes/los amigos.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.65);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .contenedor {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            max-width: 750px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #FFD700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        th {
            background-color: rgba(0,123,255,0.8);
            color: white;
        }
        td {
            font-size: 1.2em;
        }
        .btn-devolver {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.1em;
            font-weight: bold;
            color: white;
            background: linear-gradient(45deg, #ff4b2b, #ff416c);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease-in-out;
        }
        .btn-devolver:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
        }
    </style>
</head>
<body>
<div class="overlay">
    <div class="contenedor">
        <h1>📚 Inventario de la Biblioteca</h1>
        <table>
            <tr>
                <th>Libros Prestados</th>
                <th>Libros Reservados</th>
                <th>Libros Disponibles</th>
                <th>Total de Libros</th>
            </tr>
            <tr>
                <td><?= $prestados ?></td>
                <td><?= $reservados ?></td>
                <td><?= $disponibles ?></td>
                <td><?= $total_libros ?></td>
            </tr>
        </table>
        <a href="registrar_libro.php" class="btn-devolver">🔄 Devolver</a>
    </div>
</div>
</body>
</html>
<?php pg_close($conn); ?>
