<?php
// Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

// Cambiar estado o eliminar usuario
if (isset($_GET['id']) && isset($_GET['accion'])) {
    $id = (int) $_GET['id'];
    $accion = $_GET['accion'];

    if ($accion === 'activar') {
        $nuevo_estado = 'activo';
        $query = "UPDATE usuarios SET estado=$1 WHERE id=$2";
        pg_query_params($conexion, $query, [$nuevo_estado, $id]);

    } elseif ($accion === 'desactivar') {
        $nuevo_estado = 'inactivo';
        $query = "UPDATE usuarios SET estado=$1 WHERE id=$2";
        pg_query_params($conexion, $query, [$nuevo_estado, $id]);

    } elseif ($accion === 'eliminar') {
        $query = "DELETE FROM usuarios WHERE id=$1";
        pg_query_params($conexion, $query, [$id]);
    }
}

// Solo buscar si se ingresó identificación
$resultado = false;
if (!empty($_GET['buscar'])) {
    $buscar = trim($_GET['buscar']);
    $query = "SELECT * FROM usuarios 
          WHERE tipo_usuario = 'lector' 
          AND (CAST(identificacion AS TEXT) = $1 OR nombre ILIKE $2)
          ORDER BY fecha_registro DESC";
   $parametros = [$buscar,"%$buscar%"];
   $resultado = pg_query_params($conexion, $query, $parametros);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Usuario - Biblioteca</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('imagenes/lo.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 40px;
        }
        .contenedor {
            background: rgba(255, 255, 255, 0.15);
backdrop-filter: blur(10px);
-webkit-backdrop-filter: blur(10px);
border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            max-width: 1500px;
            margin: auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            color: #111; 
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        table th {
            background-color: #343a40;
            color: white;
        }
        a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            transition: background-color 0.3s;
        }
        .btn-activar { background-color: #007bff; }
        .btn-activar:hover { background-color: #0056b3; }
        .btn-desactivar { background-color: #dc3545; }
        .btn-desactivar:hover { background-color: #a71d2a; }
        .btn-eliminar { background-color: #6c757d; }
        .btn-eliminar:hover { background-color: #495057; }
        .btn-volver {
            display: inline-block;
            margin: 15px 0;
            background-color: #ffc107;
            color: #000;
            padding: 8px 14px;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-volver:hover {
            background-color: #e0a800;
        }
        .buscador {
            text-align: center;
            margin-bottom: 20px;
        }
        .buscador input {
            padding: 8px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .buscador button {
            padding: 8px 14px;
            background-color: #28a745;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
        }
        .buscador button:hover {
            background-color: #218838;
        }
        .mensaje {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-style: italic;
        }
        .acciones-botones{
    display: flex;
    justify-content: center;
    gap: 5px; /* separación entre botones */
    align-items: center;
}
.acciones-botones a{
    margin-right: 10px;
}
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Buscar Usuario por Identificación</h2>

        <!-- Buscador -->
        <div class="buscador">
            <form method="get">
                <input type="text" name="buscar" placeholder="Ingrese identificación" value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <?php if ($resultado && pg_num_rows($resultado) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Identificación</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                <?php while ($fila = pg_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['correo']) ?></td>
                        <td><?= htmlspecialchars($fila['identificacion']) ?></td>
                        <td><?= htmlspecialchars($fila['telefono']) ?></td>
                        <td><?= $fila['estado'] ?></td>
                        <td>
    <div class="acciones-botones">
        <?php if ($fila['estado'] === 'inactivo'): ?>
            <a href="?id=<?= $fila['id'] ?>&accion=activar" class="btn-activar">Activar</a>
        <?php else: ?>
            <a href="?id=<?= $fila['id'] ?>&accion=desactivar" class="btn-desactivar">Desactivar</a>
        <?php endif; ?>

        <a href="?id=<?= $fila['id'] ?>&accion=eliminar"
           class="btn-eliminar"
           onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
           Eliminar
        </a>
    </div>
</td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php elseif (isset($_GET['buscar'])): ?>
            <p class="mensaje">⚠ No se encontraron usuarios con esa identificación.</p>
        <?php else: ?>
            <p class="mensaje">Ingrese una identificación en el buscador para ver resultados.</p>
        <?php endif; ?>

        <a href="/biblioteca/biblioteca_inicio/" class="btn-volver">← Volver al inicio</a>
    </div>
</body>
</html>

<?php
pg_close($conexion);
?>
