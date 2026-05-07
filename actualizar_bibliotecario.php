<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])){
  echo "no se ha iniciado seccion";
}
  // Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}
$id= (int)$_SESSION['id'];

$nombre = $_POST['nombre'];
$apellido1 = $_POST['apellido1'];
$apellido2 = $_POST['apellido2'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
// Verificar si el usuario existe
$query = "Update usuarios Set nombre=$1, apellido1 = $2, apellido2 = $3, correo = $4, telefono = $5 Where id = $6 ";

$resultado = pg_query_params($conexion, $query,array( $nombre, $apellido1, $apellido2, $correo,$telefono,$id));

if ($resultado){
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
                window.location.href='./biblioteca_inicio';
            },5000);
            window.onload = actualizarContador;
        </script>
    </head>
    <body>
        <div class='card'>
            <div class='icono'>✅</div>
            <h2>¡Datos Actualizados!</h2>
            <p> Tus datos an sido actualizados <b>correctamente</b>.</p>
            <p class='small'>Serás redirigido a la  pagina de bibliotecario en <span id='contador' class='contador'>5</span> segundos...</p>
        </div>
    </body>
    </html>
    ";
    
}else{
    echo"Se produjo un error";
}
?>
