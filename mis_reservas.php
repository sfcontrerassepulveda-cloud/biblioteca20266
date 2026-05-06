<?php
session_start();

// Verificar que el usuario esté logueado y sea lector
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    echo "Acceso denegado.";
    exit;
}

// Conexión a la base de datos
$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

$id_lector = $_SESSION['id'];

// Consulta: calculamos fecha de expiración (24 horas después de la reserva)
$query = "
SELECT
    r.id_reserva,
    u.nombre AS usuario,
    l.titulo,
    l.autor AS autor,
    l.categoria AS categoria,
    l.año AS anio,
    r.estado AS estado,
    TO_CHAR(r.fecha_reserva, 'DD/MM/YYYY') AS fecha_reserva,
    TO_CHAR(r.fecha_expiracion, 'DD/MM/YYYY') AS fecha_expiracion
FROM reservas r
JOIN usuarios u ON r.id_usuario = u.id
JOIN libros l ON r.id_libro = l.id_libro
WHERE r.id_usuario = $1 AND (r.estado = 'activa' OR r.estado = 'pendiente')
ORDER BY r.fecha_reserva DESC;
";

$resultado = pg_query_params($conexion, $query, [$id_lector]);

if (!$resultado) {
    echo "Error en la consulta: " . pg_last_error($conexion);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="css/estilo_tabla.css">
    <style>
        .btn-cancelar {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-cancelar:hover {
            background-color: #c0392b;
        }
        .expirada {
            color: red;
            font-weight: bold;
        }
        .valida {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>📖 Mis Reservas</h2>

        <?php if (isset($_GET['msg'])) { ?>
            <p style="color: green; text-align:center;"><?= htmlspecialchars($_GET['msg']); ?></p>
        <?php } ?>

        <?php if (pg_num_rows($resultado) > 0) { ?>
            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID Reserva</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Año</th>
                            <th>Categoría</th>
                            <th>Fecha de Reserva</th>
                            <th>Fecha de Expiración</th>
                            <th>Estado</th>
                            <th>Acción</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = pg_fetch_assoc($resultado)) { 
                            //Verificar si está expirada
                             
                            //$expiracion = strtotime($fila['fecha_expiracion']);
                           
                           // $ahora = time();
                          //$expiracion = new DateTime($fila['fecha_expiracion']);
                          $expiracion = $fila["fecha_expiracion"];
                          $ahora = date("d-m-Y");
                          // $ahora = new DateTime();

                         $esta_expirada = $ahora > $expiracion;
                        
                        
                        ?>
                            <tr>
                                <td><?= $fila['id_reserva']; ?></td>
                                <td><?= htmlspecialchars($fila['titulo']); ?></td>
                                <td><?= htmlspecialchars($fila['autor']); ?></td>
                                <td><?= $fila['anio']; ?></td>
                                <td><?= htmlspecialchars($fila['categoria']); ?></td>
                                <td><?= $fila['fecha_reserva']; ?></td>
                                <td class="<?= $esta_expirada ? 'expirada' : 'valida'; ?>">
                                    <?= $fila['fecha_expiracion']; ?>
                                </td>
                                <td>
                                    <?php if($fila['estado'] == 'activa'){ ?>      
                                        <?= $esta_expirada ? "Expirada" : "Pendiente"; ?>
                                    <?php } elseif($fila['estado'] == 'aceptada'){ ?>  
                                        Aceptada
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($fila['estado'] == 'activa' && !$esta_expirada) { ?>
                                        <form method="POST" action="reservar_lector_negar.php" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                            <input type="hidden" name="id_reserva" value="<?= $fila['id_reserva']; ?>">
                                            <button type="submit" class="btn-cancelar">❌ Cancelar</button>
                                        </form>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                             
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p style="text-align:center;">No tienes reservas registradas.</p>
        <?php } ?>

        <div class="botones-inferiores">
            <a href="lector" class="btn-volver">← Volver al inicio lector</a>
        </div>
    </div>
</body>
</html>
