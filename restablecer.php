<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nueva = $_POST["nueva"];
    $confirmar = $_POST["confirmar"];

    if ($nueva === $confirmar) {
        // Guardar en un archivo de texto (simulación)
        file_put_contents("nueva_contraseña.txt", password_hash($nueva, PASSWORD_DEFAULT));
        $mensaje = "✅ Contraseña cambiada correctamente.";
    } else {
        $mensaje = "❌ Las contraseñas no coinciden.";
    }
}

$token = $_GET["token"] ?? "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>
    <?php if ($token): ?>
    <form method="POST">
        <label>Nueva contraseña:</label>
        <input type="password" name="nueva" required><br>
        <label>Confirmar contraseña:</label>
        <input type="password" name="confirmar" required><br>
        <button type="submit">Cambiar contraseña</button>
    </form>
    <?php endif; ?>
    <p><?php echo $mensaje; ?></p>
</body>
</html>
