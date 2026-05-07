<?php
session_start();

// Solo bibliotecario
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

// Conexión BD
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("Error de conexión");
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar'])) {
    $id_libro = (int) $_POST['id_libro'];
    $params = [
        $_POST['titulo'], $_POST['autor'], $_POST['año'],
        $_POST['categoria'], $_POST['isbn'], $_POST['ubicacion'],
        $_POST['estado'], $id_libro
    ];
    $query = "UPDATE libros
              SET titulo=$1, autor=$2, año=$3, categoria=$4, isbn=$5, ubicacion=$6, estado=$7
              WHERE id_libro=$8";
    pg_query_params($conexion, $query, $params);

    header("Location: ver_libros.php?mensaje=actualizado");
    exit;
}

// Cargar datos si viene id_libro
if (isset($_POST['id_libro'])) {
    $resultado = pg_query_params($conexion, "SELECT * FROM libros WHERE id_libro=$1", [(int) $_POST['id_libro']]);
    if (pg_num_rows($resultado) == 1) {
        $libro = pg_fetch_assoc($resultado);
    } else {
        echo "Libro no encontrado";
        exit;
    }
} else {
    echo "ID no válido";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Libro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            width: 320px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 12px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #4facfe;
            border: none;
            color: white;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #009dff;
        }
        .cancelar {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #555;
            text-decoration: none;
        }
        .cancelar:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Editar Libro</h2>
    <form method="post" action="actualizar_libro.php">
        <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?>">

        <label>Título:</label>
        <input type="text" name="titulo" value="<?= htmlspecialchars($libro['titulo']) ?>" required>

        <label>Autor:</label>
        <input type="text" name="autor" value="<?= htmlspecialchars($libro['autor']) ?>" required>

        <label>Año:</label>
        <input type="number" name="año" value="<?= $libro['año'] ?>" required>

        <label>Categoría:</label>
        <input type="text" name="categoria" value="<?= htmlspecialchars($libro['categoria']) ?>" required>

        <label>ISBN:</label>
        <input type="text" name="isbn" value="<?= htmlspecialchars($libro['isbn']) ?>" required>

        <label>Ubicación:</label>
        <input type="text" name="ubicacion" value="<?= htmlspecialchars($libro['ubicacion']) ?>" required>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="disponible" <?= $libro['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="prestado" <?= $libro['estado'] == 'prestado' ? 'selected' : '' ?>>Prestado</option>
            <option value="reservado" <?= $libro['estado'] == 'reservado' ? 'selected' : '' ?>>Reservado</option>
        </select>

        <button type="submit" name="guardar">💾 Guardar</button>
    </form>
    <a href="ver_libros.php" class="cancelar">Cancelar</a>
</div>
</body>
</html>
