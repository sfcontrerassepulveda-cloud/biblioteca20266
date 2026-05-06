<?php
$host = "localhost";
$dbname = "biblioteca2025";
$user = "postgres";
$password = "1234"; // Cambia esto si tu contraseña es diferente

try {
    $conexion = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
    exit; // <- aquí ya está limpio
}
?>
