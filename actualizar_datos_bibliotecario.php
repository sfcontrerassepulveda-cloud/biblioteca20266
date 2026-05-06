<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])){
  echo "no se ha iniciado seccion";
}
  // Conexión a la base de datos
$conexion = pg_connect("host=localhost dbname=biblioteca2025 user=postgres password=1234");
if (!$conexion) {
    die("Error al conectar a la base de datos.");
}

// Verificar si el usuario existe
$id = (int) $_SESSION['id'];

$query = "SELECT * FROM usuarios WHERE id = $1 LIMIT 1";
$resultado = pg_query_params($conexion, $query,array($id) );
$usuario = pg_fetch_assoc($resultado)
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualizar datos del bibliotecario</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      width: 380px;
      border: 2px solid #d1b894;
    }
    .form-container h2 {
      text-align: center;
      color: #8b5e34;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: #333;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .btn {
      width: 100%;
      padding: 12px;
      background: #8b5e34;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn:hover {
      background: #6f4728;
    }
    .back {
      display: block;
      text-align: center;
      margin-top: 15px;
      text-decoration: none;
      color: #8b5e34;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>📚 Actualizar mis datos Bibliotecario</h2>
    <form action="actualizar_bibliotecario.php" method="post">
      
      <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre'] ?>" required>
      </div>

      <div class="form-group">
        <label for="apellido1">Primer Apellido:</label>
        <input type="text" id="apellido1" name="apellido1" value="<?php echo $usuario['apellido1'] ?>" required>
      </div>

      <div class="form-group">
        <label for="apellido2">Segundo Apellido:</label>
        <input type="text" id="apellido2" name="apellido2" value="<?php echo $usuario['apellido2'] ?>" required>
      </div>

      <div class="form-group">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo"value="<?php echo $usuario['correo'] ?>" required>
      </div>

      <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo $usuario['telefono'] ?>" required>
      </div>

      <button type="submit" class="btn">💾 Guardar cambios</button>
    </form>
    <a href=http://localhost/biblioteca/biblioteca_inicio/ class="back">← Volver al inicio</a>
  </div>
</body>
</html>
