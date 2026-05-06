<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo_usuario = "usuario@biblioteca.com"; // 📌 Correo válido predefinido
    $correo = trim($_POST["correo"]);

    if ($correo === $correo_usuario) {
        // Generar enlace con token simple
        $token = bin2hex(random_bytes(8)); // token aleatorio
        $enlace = "http://localhost/biblioteca/restablecer.php?token=" . $token;

        // Enviar correo
        $asunto = "Recuperación de contraseña - Biblioteca";
        $mensajeCorreo = "
        <h2>Recuperación de contraseña</h2>
        <p>Haga clic en el siguiente enlace para restablecer su contraseña:</p>
        <a href='$enlace'>$enlace</a>
        ";

        $cabeceras = "MIME-Version: 1.0" . "\r\n";
        $cabeceras .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $cabeceras .= "From: Biblioteca <no-reply@biblioteca.com>" . "\r\n";

        // mail($correo, $asunto, $mensajeCorreo, $cabeceras); // Descomenta para enviar
        $mensaje = "✅ Se ha enviado un enlace de recuperación a tu correo (simulado).<br>Enlace: <a href='$enlace'>$enlace</a>";
    } else {
        $mensaje = "❌ El correo no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
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
            padding: 40px 45px;
            border-radius: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
            max-width: 450px;
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
            margin-bottom: 25px;
            color: #5b4636;
            font-size: 1.6em;
        }
        label {
            display: block;
            font-weight: bold;
            color: #6a5243;
            margin-bottom: 8px;
            text-align: left;
            font-size: 1.1em;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 20px;
            border: 1px solid #caa26a;
            border-radius: 10px;
            background-color: #fdfaf6;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-size: 1em;
            box-sizing: border-box;
        }
        input:focus {
            border-color: #b88654;
            box-shadow: 0 0 8px rgba(184, 134, 84, 0.6);
            outline: none;
        }

        /* 🔹 Animaciones */
        @keyframes subir {
            from { transform: translateY(40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes latido {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        button {
            width: 100%;
            background: linear-gradient(135deg, #caa26a, #b88654);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            animation: subir 0.8s ease-out, latido 2s infinite ease-in-out;
        }
        button:hover {
            background: linear-gradient(135deg, #b88654, #a26a3f);
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        p {
            margin-top: 18px;
            color: #333;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>📚 Recuperar Contraseña</h2>
        <form method="POST" action="mailer.php">
            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required>
            <button type="submit">Enviar enlace</button>
        </form>
        <p><?php echo $mensaje ?? ''; ?></p>
    </div>
</body>
</html>
