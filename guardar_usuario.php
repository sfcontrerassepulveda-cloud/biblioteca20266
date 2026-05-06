<?php
// Conexión a la base de datos
$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");

if (!$conexion) {
    die("Error al conectar a la base de datos.");
}
session_start();
// Capturar y limpiar datos del formulario
$nombre = isset($_POST['nombre']) ? strtolower(trim($_POST['nombre'])) : '';
$apellido1 = isset($_POST['apellido1']) ? strtolower(trim($_POST['apellido1'])) : '';
$apellido2 = isset($_POST['apellido2']) ? strtolower(trim($_POST['apellido2'])) : '';
$correo = isset($_POST['correo']) ? strtolower(trim($_POST['correo'])) : '';
$contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
$identificacion = isset($_POST['identificacion']) ? trim($_POST['identificacion']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

// Validaciones básicas
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo electrónico no válido.");
}
if (!preg_match('/^\d{1,20}$/', $identificacion)) {
    die("Identificación debe ser un número de hasta 20 dígitos.");
}
//if (!preg_match('/^\d{1,10}$/', $telefono)) {
 //   die("Teléfono debe ser un número de hasta 10 numeros.");
//}

// Encriptar la contraseña
$contrasena_segura = password_hash($contrasena, PASSWORD_DEFAULT);

// Verificar si ya existe ese correo o identificación
$query_check = "SELECT 1 FROM usuarios WHERE correo = $1 OR identificacion = $2";
$resultado_check = pg_query_params($conexion, $query_check, array($correo, $identificacion));
if(!ctype_digit($identificacion) ||strlen($identificacion)>10||strlen($identificacion)<10){
        $_SESSION['mensaje_identificacion'] ="La identificacion debe ser un número de hasta 10 dígitos.";
        header("Location:registro_lector.php");
        exit;
}
if (!preg_match('/^3[0-9]{9}$/', $telefono)) {
    $_SESSION['mensaje_telefono'] = "El teléfono debe ser un celular válido de 10 dígitos que empiece con 3.";
    header("Location: registro_lector.php");
    exit;
}

if (pg_num_rows($resultado_check) > 0) {
    $mensaje = "Error: Ya existe un usuario con ese correo o número de identificación.";
} else {
    // Insertar usuario
    $identificacion1 = (int)$identificacion;
    $query_insert = "INSERT INTO usuarios (
        nombre, apellido1, apellido2, correo, contrasena, identificacion, telefono, tipo_usuario, estado, fecha_registro
    ) VALUES (
        $1, $2, $3, $4, $5, $6, $7, 'lector', 'inactivo', NOW()
    )";
    $params = array($nombre, $apellido1, $apellido2, $correo, $contrasena_segura, $identificacion1, $telefono);
    $resultado_insert = pg_query_params($conexion, $query_insert, $params);

    if ($resultado_insert) {
        $mensaje = "Usuario registrado correctamente. Espera la aprobación del bibliotecario.";
    } else {
        $mensaje = "Error al registrar usuario.";
    }
}

pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
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
            padding: 30px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.2);
        }
        .card h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .card p {
            font-size: 16px;
            margin-bottom: 25px;
            color: #555;
        }
        .btn {
            display: inline-block;
            background-color: #4A90E2;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #357ABD;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Resultado del Registro</h2>
        <p><?php echo $mensaje; ?></p>
        <a href="login.php" class="btn">Regresar al Login</a>
    </div>
</body>
</html>
