<?php
$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

$correo = $_POST['correo'];
$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Verificar si el correo existe
$result = pg_query_params($conexion, "SELECT * FROM usuarios WHERE correo = $1", array($correo));

if (pg_num_rows($result) == 1) {
    // Guardar token
    pg_query_params($conexion, "UPDATE usuarios SET token_recuperacion = $1, token_expira = $2 WHERE correo = $3", array($token, $expira, $correo));

    // Enlace de recuperación
    $enlace = "http://tusitio.com/restablecer_contraseña.php?token=$token";

    // Enviar correo (usando la función mail)
    $asunto = "Recuperación de contraseña";
    $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n$enlace\nEste enlace caduca en 1 hora.";
    $cabeceras = "From: noreply@tusitio.com";

    if (mail($correo, $asunto, $mensaje, $cabeceras)) {
        echo "Enlace de recuperación enviado a tu correo.";
    } else {
        echo "Error al enviar el correo.";
    }
} else {
    echo "Correo no registrado.";
}
?>
