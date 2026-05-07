<?php
session_start();

// Verificar que haya sesión activa
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado. Solo bibliotecarios pueden ingresar.";
    exit;
}

// Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

// Consultar todos los usuarios tipo lector
$query = "SELECT id, nombre, correo, identificacion, estado FROM usuarios WHERE tipo_usuario = 'lector'";
$resultado = pg_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados - Bibliotecario</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
             background: url('imagenes/pp.png') no-repeat center center fixed;
            background-size: cover;
        }

        .contenedor {
            background-color: rgba(255, 255, 255, 0.95);
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        .contenedor img {
            width: 100px;
            margin-bottom: 10px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #dcefff;
            color: #333;
        }

        td {
            background-color: #fdfdfd;
        }

        form {
            margin: 0;
        }

        .btn-volver {
            margin-top: 20px;
            padding: 10px 25px;
            background-color: #3c4b64;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-volver:hover {
            background-color: #2c3e50;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <img src="https://cdn-icons-png.flaticon.com/512/194/194931.png" alt="Icono Bibliotecario">
        <h2>Usuarios Registrados</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Identificación</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            <?php while ($fila = pg_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $fila['id']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['correo']; ?></td>
                    <td><?php echo $fila['identificacion']; ?></td>
                    <td><?php echo ucfirst($fila['estado']); ?></td>
                    <td>
                        <?php if ($fila['estado'] == 'inactivo') { ?>
                            <form action="activar_usuario.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                <input type="submit" value="Activar">
                            </form>
                        <?php } else { ?>
                            Activo
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br>
        <a href="inicio.php"><button class="btn-volver">Volver al inicio</button></a>
    </div>
</body>
</html>
