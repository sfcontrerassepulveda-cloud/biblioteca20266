<?php
session_start();
require_once "conexion.php"; // Aquí $conexion es un PDO

// Verificar sesión
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    header("Location: login.php");
    exit;
}  

$idUsuario = $_SESSION['id']; 
$nombreLector = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Lector";

// 🚀 Consulta préstamos activos (fecha_devolucion NULL)
$sql = "
    SELECT p.id_prestamo, l.titulo AS libro, u.nombre, u.identificacion, u.correo, 
           p.fecha_prestamo,p.fecha_devolucion
    FROM prestamos p
    INNER JOIN libros l ON p.id_libro = l.id_libro
    INNER JOIN usuarios u ON p.id_usuario = u.id
    WHERE p.fecha_real_devolucion IS NULL 
      AND p.id_usuario = :id   -- ✅ Solo del usuario en sesión
    ORDER BY p.fecha_prestamo DESC
";

$stmt = $conexion->prepare($sql);
$stmt->execute([":id" => $idUsuario]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Préstamos Activos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f6fa;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #0077b6;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px auto;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,.1);
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      text-align: center;
    }
    th {
      background: #0077b6;
      color: #fff;
    }
    tr:nth-child(even) {
      background: #f9f9f9;
    }
    tr:hover {
      background: #e6f7ff;
    }
    .activo {
      color: red;
      font-weight: bold;
    }
    .btn {
      display: block;
      width: 200px;
      margin: 30px auto;
      padding: 12px;
      text-align: center;
      background: linear-gradient(135deg, #00b4d8, #0077b6);
      color: white;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(0,0,0,.2);
      transition: 0.3s;
    }
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,.3);
    }
  </style>
</head>
<body>
  <h1>📚 Préstamos Activos de <?= htmlspecialchars($nombreLector) ?></h1>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Libro</th>
        <th>Nombre</th>
        <th>Identificación</th>
        <th>Correo</th>
        <th>Fecha de Préstamo</th>
         <th>Fecha de Devolución</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result): ?>
        <?php $contador = 1; ?>
        <?php foreach ($result as $row): ?>
          <tr>
            <td><?= $contador++ ?></td>
            <td><?= htmlspecialchars($row['libro']) ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['identificacion']) ?></td>
            <td><?= htmlspecialchars($row['correo']) ?></td>
            <td><?= (new DateTime($row['fecha_prestamo']))->format('d-m-Y') ?></td>
      <td><?= (new DateTime($row['fecha_devolucion']))->format('d-m-Y') ?></td>


            <td><span class="activo">Activo</span></td>

           
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7">⚠️ No tienes préstamos activos.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="http://localhost/biblioteca/lector/" class="btn">⬅️ Regresar</a>
</body>
</html>
