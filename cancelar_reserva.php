<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

$id_reserva = $_POST['id_reserva'];
$id_libro = $_POST['id_libro'];

$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

if (!$conexion) {
    die("Error de conexión.");
}

// Cambiar estado del libro a 'disponible'
pg_query($conexion, "UPDATE libros SET estado = 'disponible' WHERE id_libro = $id_libro");
// Inactivar la reserva
pg_query($conexion, "UPDATE reservas SET estado = 'inactiva' WHERE id_reserva = $id_reserva");

pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Cancelada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .mensaje {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            max-width: 400px;
        }
        .mensaje h2 {
            color: #2ecc71;
            margin-bottom: 15px;
        }
        .mensaje p {
            color: #555;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="mensaje">
        <h2>✅ Reserva cancelada correctamente</h2>
        <p>El libro ha sido marcado como disponible y la reserva inactivada.</p>
        <a href="panel_reservas.php" class="btn">Volver al Panel</a>
    </div>
</body>
</html>
