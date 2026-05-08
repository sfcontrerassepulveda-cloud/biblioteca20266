<?php
session_start();

// Verificación de sesión
if (!isset($_SESSION['id'])) {
    echo "Acceso denegado. Debes iniciar sesión.";
    echo "<br><a href='login.php'>Iniciar sesión</a>";
    exit;
}

// Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}else {
    pg_query($conexion, "SET TIME ZONE 'America/Bogota'");
}

// Variables para resultados
$resultado = false;

// Si envió búsqueda
if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $buscar = trim($_GET['buscar']);
   $query = "SELECT * FROM libros 
          WHERE (LOWER(titulo) LIKE LOWER($1) 
                 OR LOWER(autor) LIKE LOWER($1) 
                 OR LOWER(categoria) LIKE LOWER($1))
            AND eliminado = 'no'
          ORDER BY fecha_registro DESC";

    $resultado = pg_query_params($conexion, $query, ['%' . $buscar . '%']);
} else {
    // Si es bibliotecario, mostrar todo
    if ($_SESSION['tipo_usuario'] == 'bibliotecario') {
        $query = "SELECT * FROM libros WHERE eliminado= 'no' ORDER BY fecha_registro DESC";
        $resultado = pg_query($conexion, $query);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Libros</title>
    <link rel="stylesheet" href="css/estilo_tabla.css">
</head>

<body>
    <div class="contenedor">
        <div class="cabecera">
            <h2>📚 Libros Registrados</h2>
        </div>

        <!-- Buscador -->
        <div class="buscador">
            <form method="GET" action="">
                <input type="text" name="buscar" placeholder="Buscar por título, autor o categoría"
                       value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button type="submit">🔍 Buscar</button>
            </form>
        </div>

        <?php if ($resultado && pg_num_rows($resultado) > 0) { ?>
            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Año</th>
                            <th>Categoría</th>
                            <th>ISBN</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Fecha de Registro</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($libro = pg_fetch_assoc($resultado)) { ?>
                            <tr>
                                <td><?= $libro['id_libro']; ?></td>
                                <td><?= htmlspecialchars($libro['titulo']); ?></td>
                                <td><?= htmlspecialchars($libro['autor']); ?></td>
                                <td><?= $libro['año']; ?></td>
                                <td><?= htmlspecialchars($libro['categoria']); ?></td>
                                <td><?= htmlspecialchars($libro['isbn']); ?></td>
                                <td><?= htmlspecialchars($libro['ubicacion']); ?></td>
                                <td><?= $libro['estado']; ?></td>
                                <td><?= $libro['fecha_registro']; ?></td>
                                <td>
                                    <?php if ($_SESSION['tipo_usuario'] == 'lector' && $libro['estado'] == 'disponible') { ?>
                                        <form action="reservar_libro.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro']; ?>">
                                            <input type="submit" value="Reservar">
                                        </form>
                                    <?php } elseif ($_SESSION['tipo_usuario'] == 'bibliotecario') { ?>
                                        <form action="actualizar_libro.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro']; ?>">
                                            <input type="submit" value="Actualizar">
                                        </form>
                                        <form action="eliminar_libro.php" method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres eliminar este libro?');">
                                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro']; ?>">
                                            <input type="submit" value="Eliminar" style="background-color: red; color: white;">
                                        </form>
                                    <?php } else { echo "No disponible"; } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php
        } elseif (isset($_GET['buscar'])) {
            echo "<p style='text-align:center; margin-top:20px;'>No se encontraron libros para la búsqueda.</p>";
        } elseif ($_SESSION['tipo_usuario'] == 'lector') {
            echo "<p style='text-align:center; margin-top:20px;'>Escribe el título o autor del libro que deseas encontrar.</p>";
        }
        ?>

        <div class="botones-inferiores">
            <?php if ($_SESSION['tipo_usuario'] == 'lector') { ?>
                <a href="lector" class="btn-volver">← Volver al inicio lector </a>
            <?php } else { ?>
                <a href="./biblioteca_inicio" class="btn-volver">← Volver al inicio bibliotecario </a>
            <?php } ?>
           
        </div>
    </div>
</body>
</html>
