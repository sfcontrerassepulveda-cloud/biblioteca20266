<?php
session_start();

// 1️⃣ Verificar sesión
if (!isset($_SESSION['id'])) {
    echo "Acceso denegado. Debes iniciar sesión.";
    exit;
}

// 2️⃣ Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

$id_usuario = $_SESSION['id'];

// 3️⃣ Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre   = trim($_POST['nombre']);
    $apellido1   = trim($_POST['apellido1']);
    $apellido2  = trim($_POST['apellido2']);
    $telefono = trim($_POST['telefono']);
    $correo   = trim($_POST['correo']);

    $sql_update = "UPDATE usuarios 
                   SET nombre = $1, apellido1= $2, apellido2= $3, telefono = $4, correo = $5
                   WHERE id = $6";
    $res_update = pg_query_params($conexion, $sql_update, [$nombre,$apellido1, $apellido2, $telefono, $correo, $id_usuario]);

    if ($res_update) {
        echo "<p style='color:green; text-align:center;'>✅ Datos actualizados correctamente.</p>";
        header("./lector/");
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error al actualizar los datos.</p>";
    }
}

// 4️⃣ Obtener datos actuales del usuario
$sql_select = "SELECT nombre,apellido1, apellido2, telefono, correo 
               FROM usuarios 
               WHERE id = $1";
$res_select = pg_query_params($conexion, $sql_select, [$id_usuario]);

if (!$res_select || pg_num_rows($res_select) == 0) {
    echo "No se encontraron datos del usuario.";
    exit;
}

$usuario = pg_fetch_assoc($res_select);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar mis datos</title>
    <style>
        /* === Reset básico === */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5e6d3, #f9f7f3);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .contenedor {
            background: #ffffff;
            padding: 30px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            max-width: 450px;
            width: 100%;
            border: 2px solid #d4b483;
            position: relative;
        }
        .contenedor::before {
            content: "";
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border-radius: 20px;
            background: linear-gradient(45deg, #d4b483, #caa26a);
            z-index: -1;
        }
        .contenedor h2 {
            text-align: center;
            color: #5b4636;
            margin-bottom: 20px;
            font-size: 1.6em;
            font-weight: bold;
        }
        label {
            display: block;
            font-weight: bold;
            color: #6a5243;
            margin-bottom: 6px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #caa26a;
            border-radius: 8px;
            background-color: #fdfaf6;
            transition: all 0.2s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #b88654;
            box-shadow: 0 0 6px rgba(184, 134, 84, 0.5);
            outline: none;
        }
        button[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #caa26a, #b88654);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s ease, background 0.2s ease;
        }
        button[type="submit"]:hover {
            background: linear-gradient(135deg, #b88654, #a26a3f);
            transform: translateY(-2px);
        }
        .btn-volver {
            display: inline-block;
            text-decoration: none;
            color: #5b4636;
            font-weight: bold;
            margin-top: 15px;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f5e6d3;
            border: 1px solid #d4b483;
            transition: all 0.2s ease;
        }
        .btn-volver:hover {
            background: #e4cfa3;
            transform: scale(1.05);
        }
        @media (max-width: 480px) {
            .contenedor {
                padding: 20px;
            }
            .contenedor h2 {
                font-size: 1.4em;
            }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>📚 ✏️ Actualizar mis datos personales</h2>
        <form method="POST" action="">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
                   <label for="apellido1">Primer Apellido:</label>
            <input type="text" id="apellido1" name="apellido1" 
                   value="<?= htmlspecialchars($usuario['apellido1']); ?>" required>
                   <label for="apellido2">Segundo Apellido:</label>
            <input type="text" id="apellido2" name="apellido2" 
                   value="<?= htmlspecialchars($usuario['apellido2']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" 
                   value="<?= htmlspecialchars($usuario['telefono']); ?>">

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" 
                   value="<?= htmlspecialchars($usuario['correo']); ?>" required>

            <button type="submit">💾 Guardar cambios</button>
        </form>

        <div style="margin-top:20px;">
            <?php if ($_SESSION['tipo_usuario'] == 'lector') { ?>
                <a href="lector" class="btn-volver">← Volver al inicio lector</a>
            <?php } else { ?>
                <a href="biblioteca_inicio" class="btn-volver">← Volver al inicio bibliotecario</a>
            <?php } ?>
        </div>
    </div>
</body>
</html>
