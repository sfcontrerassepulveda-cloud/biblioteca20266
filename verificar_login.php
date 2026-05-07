<?php
session_start(); 

$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@postgres.railway.internal:16774./railway");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    $query = "SELECT * FROM usuarios WHERE correo = $1 LIMIT 1";

    $resultado = pg_query_params($conexion, $query, array($correo));

    if ($resultado && pg_num_rows($resultado) === 1) {
        $usuario = pg_fetch_assoc($resultado);

        if (strtolower($usuario['estado']) !== 'activo') {
            $mensaje = "Tu cuenta aún no ha sido activada por el bibliotecario.";
        } elseif (password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            header("Location: inicio.php");
            exit;
        } else {
            // Mostrar cuadro de error de contraseña
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "No se encontró una cuenta con ese correo.";
    }
}
pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensaje - Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .mensaje-box {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0px 5px 25px rgba(0,0,0,0.3);
            width: 350px;
            text-align: center;
        }
        .mensaje-box h3 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }
        .btn-volver {
            background: #3498db;
            color: white;
        }
        .btn-volver:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje-box">
            <h3><?= $mensaje; ?></h3>
            <form action="login.php" method="get">
                <button type="submit" class="btn btn-volver">⬅ Volver</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
