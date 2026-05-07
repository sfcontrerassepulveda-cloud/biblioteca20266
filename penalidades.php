<?php
session_start();

// Conexión a PostgreSQL
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("❌ Error al conectar a la base de datos.");
}

// Verificar sesión
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['id']; 
$nombreLector = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Lector";

// Fecha actual
$hoy = date("Y-m-d");

// Consultar solo penalidades activas
$sql = "SELECT id_penalidad, fecha_inicio, fecha_final 
        FROM penalidades 
        WHERE id_usuario = $1 AND fecha_final >= $2
        ORDER BY fecha_inicio DESC";
$resultado = pg_query_params($conexion, $sql, array($idUsuario, $hoy));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Penalidades Activas</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      color: #333;
      background: linear-gradient(135deg, #a2d2ff, #cdb4db, #bde0fe);
      background-attachment: fixed;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }
    .content {
      width: 90%;
      max-width: 900px;
      margin-top: 40px;
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(6px);
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    h2 {
      color:#0077b6;
      margin-bottom:20px;
      text-align:center;
    }

    table { 
      width:100%; 
      border-collapse:collapse; 
      margin-top:20px; 
      background:#ffffff; 
      border-radius:15px; 
      overflow:hidden; 
      box-shadow:0 6px 18px rgba(0,0,0,0.2);
    }
    th, td { padding:14px; text-align:center; border-bottom:1px solid #ddd; }
    th { background:linear-gradient(90deg,#0077b6,#00b4d8); color:#fff; font-size:16px; }
    tr:last-child td { border-bottom:none; }
    tr:hover { background:#f1f1f1; }
    td { font-size:15px; }

    .btn-regresar {
      display:block;
      margin:30px auto 10px auto;
      padding:12px 25px;
      font-size:16px;
      font-weight:bold;
      color:#fff;
      background:linear-gradient(135deg,#00b4d8,#0077b6);
      border:none;
      border-radius:25px;
      cursor:pointer;
      text-decoration:none;
      transition:0.3s;
      box-shadow:0 4px 12px rgba(0,0,0,0.3);
      text-align:center;
      width:200px;
    }
    .btn-regresar:hover {
      background:linear-gradient(135deg,#0096c7,#005f73);
      transform:translateY(-3px);
      box-shadow:0 6px 16px rgba(0,0,0,0.5);
    }
  </style>
</head>
<body>
  <div class="content">
    <h2>⚠️ Penalidades Activas de <?php echo htmlspecialchars($nombreLector); ?></h2>
    
    <?php if ($resultado && pg_num_rows($resultado) > 0) { ?>
      <table>
        <tr>
          <th>ID Penalidad</th>
          <th>Fecha Inicio</th>
          <th>Fecha Final</th>
          <th>Estado</th>
        </tr>
        <?php while ($row = pg_fetch_assoc($resultado)) { ?>
          <tr>
            <td><?php echo $row['id_penalidad']; ?></td>
            <td><?php echo $row['fecha_inicio']; ?></td>
            <td><?php echo $row['fecha_final']; ?></td>
            <td>⛔ Activa</td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <p style="text-align:center; font-size:18px; color:#444; margin-top:30px;">
        ✅ No tienes penalidades activas en este momento.
      </p>
    <?php } ?>

    <!-- Botón regresar -->
    <a href="./lector/" class="btn-regresar">⬅️ Regresar</a>
  </div>
</body>
</html>
