<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

// 🔹 Actualizar penalidades automáticamente
pg_query($conexion, "
    UPDATE prestamos 
    SET penalizado = TRUE 
    WHERE estado = 'activo' 
    AND fecha_prestamo < NOW() - INTERVAL '7 days'
");

// 🔹 Consultar SOLO préstamos activos
$query = "
SELECT 
    p.id_prestamo,
    u.nombre AS nombre_usuario,
    u.identificacion,
    u.correo,
    l.titulo,
    p.fecha_prestamo,
    p.fecha_devolucion,
    p.penalizado,
    p.observaciones,
    p.fecha_real_devolucion
FROM prestamos p
JOIN usuarios u ON p.id_usuario = u.id
JOIN libros l ON p.id_libro = l.id_libro
WHERE p.estado = 'activo'
ORDER BY p.fecha_prestamo ASC
";
$resultado = pg_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Préstamos Activos</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('imagenes/pl.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .contenedor {
            background-color: rgba(255, 255, 255, 0.95);
            max-width: 95%;
            margin: 40px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn-volver {
            display: block;
            width: 200px;
            margin: 40px auto 20px;
            padding: 12px;
            background-color: #34495e;
            color: white;
            border: none;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-volver:hover {
            background-color: #2c3e50;
        }

        .btn-devolver {
            padding: 8px 14px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-devolver:hover {
            background-color: #219150;
        }

        .devuelto {
            font-style: italic;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Préstamos Activos</h2>
        

        <table>
            <tr>
                <th>Préstamo #</th>
                <th>Libro</th>
                <th>Nombre</th>
                <th>Identificación</th>
                <th>Correo</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución</th>
                <th>Penalizado</th>
                <th>Observaciones</th>
            </tr>
            <?php while ($fila = pg_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo $fila['id_prestamo']; ?></td>
                <td><?php echo $fila['titulo']; ?></td>
                <td><?php echo $fila['nombre_usuario']; ?></td>
                <td><?php echo $fila['identificacion']; ?></td>
                <td><?php echo $fila['correo']; ?></td>
                <td><?php echo $fila['fecha_prestamo']; ?></td>
                <td><?php echo $fila['fecha_devolucion'] ?: '—'; ?></td>
                <td><?php if ( $fila['penalizado']=== 't'|| $fila['fecha_prestamo'] > $fila['fecha_devolucion'] ){?>
                    ✅ Sí
                    <?php 
                } else {  ?>
                     ❌ No
          <?php       } ?> 
                   
               
        
            </td>

                <td>
                    <?php if (empty($fila['fecha_real_devolucion'])): ?>
                        <form action="devolver_libro.php" method="post" style="display:inline;">
                            <input type="hidden" name="id_prestamo" value="<?php echo $fila['id_prestamo']; ?>">

                            <select name="observacion" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="buen estado" <?php if($fila['observaciones']=='buen estado') echo 'selected'; ?>>Buen estado</option>
                                <option value="libro dañado" <?php if($fila['observaciones']=='libro dañado') echo 'selected'; ?>>Libro dañado</option>
                                <option value="hoja rota" <?php if($fila['observaciones']=='hoja rota') echo 'selected'; ?>>Hoja rota</option>
                                <option value="libro rayado" <?php if($fila['observaciones']=='libro rayado') echo 'selected'; ?>>Libro rayado</option>
                                <option value="faltan hojas" <?php if($fila['observaciones']=='faltan hojas') echo 'selected'; ?>>Faltan hojas</option>
                            </select>

                            <input type="submit" value="Devolver" class="btn-devolver">
                        </form>
                    <?php else: ?>
                        <span class="devuelto">Devuelto<br>
                            <?php echo $fila['observaciones'] ? "📌 ".$fila['observaciones'] : "📌 Sin observación"; ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php } ?> 
           
       
        </table>

        <a href="http://localhost/biblioteca/biblioteca_inicio/" class="btn-volver">Volver al inicio</a>
    </div>
</body>
</html>
