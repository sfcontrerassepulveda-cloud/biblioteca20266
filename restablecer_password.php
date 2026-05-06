<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass1 = trim($_POST["pass1"]);
    $pass2 = trim($_POST["pass2"]);

    if ($pass1 === $pass2) {
        // Aquí iría la lógica para guardar la nueva contraseña en la base de datos
        // Ejemplo: UPDATE usuarios SET password = password_hash($pass1, PASSWORD_DEFAULT) WHERE id = X;

        $mensaje = "✅ Tu contraseña ha sido cambiada correctamente.";
    } else {
        $mensaje = "❌ Las contraseñas no coinciden.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #f5e6d3, #f9f7f3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .contenedor {
            background: #ffffff;
            padding: 30px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            width: 100%;
            border: 2px solid #d4b483;
            text-align: center;
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
        h2 {
            margin-bottom: 20px;
            color: #5b4636;
        }
        label {
            display: block;
            font-weight: bold;
            color: #6a5243;
            margin-bottom: 6px;
            text-align: left;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #caa26a;
            border-radius: 8px;
            background-color: #fdfaf6;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        input[type="password"]:focus {
            border-color: #b88654;
            box-shadow: 0 0 6px rgba(184, 134, 84, 0.5);
            outline: none;
        }
        button {
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
        button:hover {
            background: linear-gradient(135deg, #b88654, #a26a3f);
            transform: translateY(-2px);
        }
        p {
            margin-top: 15px;
            color: #333;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>🔑 Cambiar Contraseña</h2>
        <form method="POST" action="./recuperar_pass.php">
            <label>Nueva contraseña:</label>
            <input type="password" name="pass1" placeholder="Ingresa tu nueva contraseña" required>

            <label>Confirmar contraseña:</label>
            <input type="password" name="pass2" placeholder="Repite la nueva contraseña" required>
             <input type="hidden" name="token" value="<?php echo $_GET['token'];?>" placeholder="Ingresa tu nueva contraseña" required>


            <button type="submit">Guardar contraseña</button>
        </form>
        <p><?php echo $mensaje; ?></p>
    </div>
</body>
</html>
