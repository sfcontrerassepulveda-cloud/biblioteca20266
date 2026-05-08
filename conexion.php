<?php
$host = "trolley.proxy.rlwy.net";
$dbname = "railway";
$user = "postgres";
$password = "TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW"; // Cambia esto si tu contraseña es diferente

try {
 $conexion = new PDO("pgsql:host=$host;dbname=$dbname; sslmode=require", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
    exit; // <- aquí ya está limpio
}
?>
