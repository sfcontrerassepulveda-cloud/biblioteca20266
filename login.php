<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login-estilo.css">
    <script src="javascript/login-script.js" defer></script>
    <style>
        .olvide-contrasena {
            margin-top: 10px;
            text-align: center;
        }

        .olvide-contrasena a {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .olvide-contrasena a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Inicio de Sesión</h1>
        <form action="verificar_login.php" method="post" autocomplete="off">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" required>

            <input type="submit" value="Ingresar">
        </form>

       <p>
        ¿No tienes cuenta?  
        <a href="registro_lector.php">Regístrate aquí</a>
    </p>

    <p>
        ¿Olvidaste tu contraseña?  
        <a href="recuperar_contraseña.php">Recupérala aquí</a>
    </p>

    </div>
</body>
</html>
