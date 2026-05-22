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
        .titulo-contenedor{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
}

.btn-regresar{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    /* background: #2563eb; */
    color: black;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.btn-regresar:hover{
    background: gray;
    color: white;
    transform: translateY(-2px) scale(1.05);
}

.btn-regresar svg{
    width: 24px;
    height: 24px;
}
    </style>
</head>
<body>
    <div class="login-container">
       <div class="titulo-contenedor">
    <a href="./" class="btn-regresar">
        <svg xmlns="http://www.w3.org/2000/svg" 
             width="22" 
             height="22" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M15 19l-7-7 7-7"/>
        </svg>
    </a>

    <h1>Inicio de Sesión</h1>
</div>
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
