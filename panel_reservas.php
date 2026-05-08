<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado. Solo bibliotecarios pueden ver este panel.";
    exit;
}

$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar con la base de datos.");
}

/* ============================================================
   1. Cancelar automáticamente reservas con más de 1 día
   ============================================================ */
$auto_cancelar = "
    UPDATE reservas
    SET estado = 'cancelada'
    WHERE estado = 'activa'
    AND fecha_reserva < (NOW() - INTERVAL '1 day')
";
pg_query($conexion, $auto_cancelar);

/* ============================================================
   2. Poner los libros de esas reservas como 'disponible'
   ============================================================ */
$liberar = "
    UPDATE libros
    SET estado = 'disponible'
    WHERE id_libro IN (
        SELECT id_libro FROM reservas
        WHERE estado = 'cancelada'
        AND fecha_reserva < (NOW() - INTERVAL '1 day')
    )
";
pg_query($conexion, $liberar);

/* ============================================================
   3. Traer las reservas activas (después de limpieza)
   ============================================================ */
$query = "
SELECT 
    r.id_reserva, 
    r.fecha_reserva, 
    u.nombre AS nombre_usuario, 
    u.identificacion, 
    u.correo, 
    l.titulo, 
    l.id_libro
FROM reservas r
JOIN usuarios u ON r.id_usuario = u.id
JOIN libros l ON r.id_libro = l.id_libro
WHERE r.estado = 'activa'
ORDER BY r.fecha_reserva ASC
";

$reservas = pg_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Reservas - Bibliotecario</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('imagenes/gemi.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 40px 10px;
        }

        .contenedor {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 95%;
            max-width: 1000px;
        }

        .portada {
            text-align: center;
            margin-bottom: 20px;
        }

        .portada img {
            max-height: 120px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #dbeafe;
            color: #1e3a8a;
        }

        input[type="submit"] {
            padding: 6px 12px;
            margin: 3px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #1d4ed8;
        }

        .volver {
            text-align: center;
            margin-top: 25px;
        }

        .volver a {
            background-color: #6b7280;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .volver a:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <div class="portada">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Bibliotecario">
    </div>
    <h2>Reservas Activas</h2>
    <table>
        <tr>
            <th>Reserva #</th>
            <th>Libro</th>
            <th>Nombre</th>
            <th>Identificación</th>
            <th>Correo</th>
            <th>Fecha de Reserva</th>
            <th>Acciones</th>
        </tr>
        <?php while ($reserva = pg_fetch_assoc($reservas)) { ?>
        <tr>
            <td><?php echo $reserva['id_reserva']; ?></td>
            <td><?php echo $reserva['titulo']; ?></td>
            <td><?php echo $reserva['nombre_usuario']; ?></td>
            <td><?php echo $reserva['identificacion']; ?></td>
            <td><?php echo $reserva['correo']; ?></td>
            <td><?php echo $reserva['fecha_reserva']; ?></td>
            <td>
                <form action="confirmar_prestamo.php" method="post" style="display:inline;">
                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                    <input type="hidden" name="id_libro" value="<?php echo $reserva['id_libro']; ?>">
                    <input type="hidden" name="email" value="<?php echo $reserva['correo']; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $reserva['nombre_usuario']; ?>">
                    <input type="hidden" name="titulo" value="<?php echo $reserva['titulo']; ?>">
                    <input type="submit" value="Confirmar préstamo">
                </form>
                <form action="cancelar_reserva.php" method="post" style="display:inline;">
                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                    <input type="hidden" name="id_libro" value="<?php echo $reserva['id_libro']; ?>">
                    <input type="hidden" name="email" value="<?php echo $reserva['correo']; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $reserva['nombre_usuario']; ?>">
                    <input type="hidden" name="titulo" value="<?php echo $reserva['titulo']; ?>">
                    <input type="submit" value="Cancelar reserva">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <div class="volver">
        <a href="./biblioteca_inicio">Volver al inicio</a>
    </div>
</div>

</body>
</html>
