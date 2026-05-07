<?php
require './vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

$correo = $_POST['correo'];

// Verificar si el usuario existe
$query = "SELECT * FROM usuarios WHERE correo = $1 LIMIT 1";
$resultado = pg_query_params($conexion, $query, array($correo));

if (pg_num_rows($resultado) == 0) {
    $estado = "error";
    $mensaje = "No existe una cuenta registrada con ese correo.";
} else {
    $token = bin2hex(random_bytes(32));
    $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $query = "INSERT INTO recuperar_pass(correo, token, expira) VALUES ($1, $2, $3)";
    pg_query_params($conexion, $query, array($correo, $token, $expira));

    $link = "./restablecer_password.php?token=$token";

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'saulocontreras2025@gmail.com';
        $mail->Password   = 'veec gujc qxnc vfsi'; // 📌 Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Remitente y destinatario
        $mail->setFrom('saulocontreras2025@gmail.com', 'Biblioteca');
        $mail->addAddress($correo);


        $mail->AddEmbeddedImage('./imagenes/biblioteca_saulo.jpg', 'biblioteca');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body    = "
        <div style='background-color:#f4f4f4; padding: 30px; font-family:arial:color:#333;'>
        <div style='background-color:#fff; padding: 20px; margin:auto; max: width: 600px;border: radius 10px;'>
         <h2>Recuperación de contraseña</h2>
       <img src='cid:biblioteca' alt='Biblioteca Saulo' style = 'display:block; margin:auto; width: 400px;'height: 400px; >
            <p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
            <a href='$link'>$link</a>
            <p>Este enlace expirará en 1 hora.</p>
        </div>
        </div>     
        ";
        $mail->AltBody = 'Enlace para recuperar contraseña: ' . $link;

        $mail->send();
        $estado = "exito";
        $mensaje = "Se ha enviado un correo con las instrucciones para restablecer tu contraseña.";
    } catch (Exception $e) {
        $estado = "error";
        $mensaje = "No se pudo enviar el mensaje. Error: {$mail->ErrorInfo}";
    }
}

pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperación de Contraseña</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .card {
        background: white;
        border-radius: 15px;
        padding: 30px 40px;
        max-width: 450px;
        text-align: center;
        box-shadow: 0px 4px 20px rgba(0,0,0,0.2);
        animation: aparecer 0.4s ease-in-out;
    }
    @keyframes aparecer {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .icon {
        font-size: 50px;
        margin-bottom: 15px;
    }
    .exito { color: #2ecc71; }
    .error { color: #e74c3c; }
    h2 { margin-bottom: 15px; }
    p { font-size: 16px; color: #555; }
    .btn {
        display: inline-block;
        margin-top: 20px;
        background-color: #4A90E2;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn:hover {
        background-color: #357ABD;
        transform: translateY(-2px);
    }
</style>
</head>
<body>
    <div class="card">
        <div class="icon <?php echo $estado; ?>">
            <?php echo ($estado == 'exito') ? '✅' : '❌'; ?>
        </div>
        <h2><?php echo ($estado == 'exito') ? 'Correo Enviado' : 'Error al Enviar'; ?></h2>
        <p><?php echo $mensaje; ?></p>
        
    </div>
</body>
</html>
