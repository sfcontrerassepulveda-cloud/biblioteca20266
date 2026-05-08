<?php
$dsn = "pgsql:host=trolley.proxy.rlwy.net;port=16774;dbname=railway";
    $usuario = "postgres";
    $password = "TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW";
try {
$conexion = new PDO($dsn, $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
    exit; // <- aquí ya está limpio
}
?>
