<?php
session_start(); // Inicia la sesión

// Conectar con la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}
$token = $_POST['token'];

// Consulta segura para buscar el usuario
$query = "SELECT * FROM recuperar_pass WHERE token= $1 LIMIT 1";
$resultado =  pg_query_params($conexion, $query, array($token)); 
if ($resultado && pg_num_rows($resultado) > 0) {
    $fila = pg_fetch_assoc($resultado); // Extrae los datos como array asociativo
    $correo = $fila['correo'];
   
} else {
    echo "❌ Token inválido o no encontrado.";
}
$pass1= $_POST['pass1'];
$pass2= $_POST['pass2'];
$pass=password_hash($pass1,PASSWORD_DEFAULT);



if ($pass1 === $pass2) {
   try {
            // 3️⃣ Actualizar contraseña en la tabla usuarios
            $query2 = "UPDATE usuarios SET contrasena = $1 WHERE correo = $2";
            $resultado2 = pg_query_params($conexion, $query2, array($pass, $correo));

           if ($resultado2 && pg_affected_rows($resultado2) > 0) {
    echo "
    <html>
    <head>
        <meta charset='utf-8'>
        <title>Contraseña actualizada</title>
        <style>
            body {
                background: linear-gradient(135deg, #f5e6d3, #f9f7f3);
                font-family: 'Segoe UI', Tahoma, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .card {
                background: #fff;
                padding: 40px;
                border-radius: 16px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                text-align: center;
                max-width: 420px;
                border: 2px solid #d4b483;
                animation: aparecer 0.8s ease-out;
            }
            @keyframes aparecer {
                from { transform: translateY(40px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .icono {
                font-size: 60px;
                color: #28a745;
                margin-bottom: 20px;
            }
            h2 {
                color: #5b4636;
                margin-bottom: 10px;
            }
            p {
                color: #333;
                font-size: 1.1em;
                margin-bottom: 12px;
            }
            .small {
                font-size: 0.9em;
                color: #777;
            }
            .contador {
                font-weight: bold;
                color: #b88654;
            }
        </style>
        <script>
            let segundos = 5;
            function actualizarContador(){
                document.getElementById('contador').innerText = segundos;
                if(segundos > 0){
                    segundos--;
                    setTimeout(actualizarContador,1000);
                }
            }
            setTimeout(function(){
                window.location.href='login.php';
            },5000);
            window.onload = actualizarContador;
        </script>
    </head>
    <body>
        <div class='card'>
            <div class='icono'>✅</div>
            <h2>¡Contraseña cambiada!</h2>
            <p>Tu contraseña se ha actualizado <b>correctamente</b>.</p>
            <p class='small'>Serás redirigido al login en <span id='contador' class='contador'>5</span> segundos...</p>
        </div>
    </body>
    </html>
    ";

                

                // 4️⃣ Borrar token para que no pueda usarse de nuevo
                pg_query_params($conexion, "DELETE FROM recuperar_pass WHERE token = $1", array($token));
            } else {
                echo "❌ No se pudo cambiar la contraseña. Error: " . pg_last_error($conexion);
            }
        } catch (Exception $e) {
            echo "⚠️ Error: " . $e->getMessage();
        }
} else {
    echo "❌ Las contraseñas no coinciden";
}

?>
