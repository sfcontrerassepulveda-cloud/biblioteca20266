<?php
session_start();

// 🔒 Verificar que el usuario sea bibliotecario
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado.";
    exit;
}

// 🔗 Conexión a la base de datos
$conexion = pg_connect("postgresql://postgres:TLlzVltHnMUWvkOawjQeRKjmQBGBnjzW@trolley.proxy.rlwy.net:16774/railway");
if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

// 📊 Consulta penalidades activas (basado en fechas)
$consulta = pg_query($conexion, "
    SELECT p.id_penalidad, p.fecha_inicio, p.fecha_final, u.nombre AS lector
    FROM penalidades p
    INNER JOIN usuarios u ON p.id_usuario = u.id
    WHERE p.fecha_final >= CURRENT_DATE
    ORDER BY p.fecha_inicio DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Penalidades Activas - Bibliotecario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #a8c0ff, #fbc2eb);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .contenedor {
      background: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
      width: 90%;
      max-width: 1000px;
    }
    h2 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      text-align: center;
    }
    th {
      background: #0096c7;
      color: white;
    }
    tr:nth-child(even) {
      background: #f1f9ff;
    }
    .estado-activo {
      color: red;
      font-weight: bold;
    }
    .btn-regresar {
      display: block;
      margin: 25px auto 0;
      padding: 12px 25px;
      background: #0096c7;
      color: white;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-size: 16px;
      text-decoration: none;
      transition: 0.3s;
      text-align: center;
      width: 200px;
    }
    .btn-regresar:hover {
      background: #0077b6;
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <h2>📋 Penalidades Activas de Lectores</h2>
    <table>
      <tr>
        <th>ID Penalidad</th>
        <th>Lector</th>
        <th>Fecha Inicio</th>
        <th>Fecha Final</th>
        <th>Estado</th>
      </tr>
      <?php
      if ($consulta && pg_num_rows($consulta) > 0) {
          while ($fila = pg_fetch_assoc($consulta)) {
              echo "<tr>
                      <td>{$fila['id_penalidad']}</td>
                      <td>{$fila['lector']}</td>
                      <td>{$fila['fecha_inicio']}</td>
                      <td>{$fila['fecha_final']}</td>
                      <td class='estado-activo'><i class='bi bi-x-circle'></i> Activa</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='5'>✅ No hay penalidades activas</td></tr>";
      }
      ?>
    </table>
    <a href='./biblioteca_inicio/' class='btn-regresar'><i class='bi bi-arrow-left'></i> Regresar</a>
  </div>
</body>
</html>
