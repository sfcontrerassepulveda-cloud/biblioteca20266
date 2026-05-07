<?php
session_start();

// Si no hay sesión, redirigir al login
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'lector') {
    header("Location: login.php");
    exit;
}

// Nombre del lector guardado en sesión
$nombreLector = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Lector";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Biblioteca Web - Lector</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #2c2c2c;
      color: #fff;
    }
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #1e1e1e, #111);
      position: fixed;
      inset: 0 auto 0 0;
      padding: 20px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.4);
    }
    /* 🔹 Título de la barra lateral con estilo */
   .sidebar h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 22px;
  letter-spacing: 1.5px;
  font-weight: bold;
  background: linear-gradient(90deg, #00b4d8, #0077b6);
  -webkit-background-clip: text;
  background-clip: text; /* soporte estándar */
  -webkit-text-fill-color: transparent;
  color: transparent; /* elimina el borde raro */
}
    .logo {
      display: flex;
      justify-content: flex-start;
      margin: 0 auto 5px;
      width: 60px;
      height: 60px;
      border-radius: 10px;
      left: 10px;
    }

    .empresa{
      display: flex;
      align-items: center;
      gap: 10px;
    }
/* --- Menú lateral elegante --- */
    .menu {
      margin-top: 25px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .menu-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 15px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(6px);
      color: #fff;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .menu-item .icon {
      font-size: 20px;
    }
    .menu-item:hover {
      transform: translateX(8px);
      background: linear-gradient(135deg, #00b4d8, #0077b6);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.5);
    }

    .content {
      margin-left: 20px;
      padding: 20px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(90deg, #0077b6, #00b4d8);
      padding: 15px 25px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      position: relative;
    }
    .header h1 {
      margin: 0;
      font-size: 22px;
    }

    /* --- Usuario con menú desplegable elegante --- */
    .header .user {
      font-weight: bold;
      cursor: pointer;
      position: relative;
      user-select: none;
      padding: 8px 15px;
      border-radius: 20px;
      background: rgba(255,255,255,0.15);
      transition: 0.3s;
    }
    .header .user:hover {
      background: rgba(255,255,255,0.25);
    }

    /* --- Menú desplegable --- */
    .dropdown {
      display: none;
      position: absolute;
      top: 55px;
      right: 0;
      background: #1e1e1e;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
      overflow: hidden;
      min-width: 200px;
      z-index: 1000;
      animation: fadeIn 0.25s ease;
    }
    .dropdown a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      color: #fff;
      text-decoration: none;
      transition: 0.3s;
      font-weight: 500;
    }
    .dropdown a:hover {
      background: linear-gradient(135deg, #00b4d8, #0077b6);
    }

    /* Animación suave */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Cards */
    .cards {
      margin-top: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
      justify-content: center;
    }
    .card {
      background: #1e1e1e;
      border-radius: 15px;
      text-align: center;
      padding: 20px;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
    }
    .card-img-usuario{
      width:100%;height:130px;background-image:url('../imagenes/libros.jpg');
      border-radius:10px;margin-bottom:12px;background-size:cover;background-position:center;
    }
    .card-img-penalidades{
      width:100%;height:130px;background-image:url('../imagenes/penalidades.jpg');
      border-radius:10px;margin-bottom:12px;background-size:cover;background-position:center;
    }
    .card-img-reservas{
      width:100%;height:130px;background-image:url('../imagenes/reservas.jpg');
      border-radius:10px;margin-bottom:12px;background-size:cover;background-position:center;
    }
    .card-img-prestamos{
      width:100%;height:130px;background-image:url('../imagenes/prestamos.jpg');
      border-radius:10px;margin-bottom:12px;background-size:cover;background-position:center;
    }
    .card h3 {
      font-weight: bold;
      color: #eee;
      font-size: 16px;
    }
    .card a {
      text-decoration: none;
      color: inherit;
      display: block;
    }
  </style>
</head>
<body>
  
    

  <!-- Contenido -->
  <div class="content">
    <div class="header">
      <div class="empresa"><img src="./logo.webp" alt="Logo Biblioteca" class="logo" />
    <h2>SISTEMA BIBLIOTECARIO</h2></div>
    
      <!-- Nombre del lector con menú -->
      <div class="user" onclick="toggleMenu()">
        👤 <?php echo htmlspecialchars($nombreLector); ?>
        <div class="dropdown" id="userMenu">
          <a href="../actualizar_mis_datos.php">✏️ Actualizar datos</a>
          <a href="../actualizar_lector.html">🔑 Actualizar contraseña</a>
          <a href="../logout.php">🚪 Cerrar sesión</a>
        </div>
      </div>
    </div>

    <!-- Cards -->
    <div class="cards">
      <div class="card" onclick="window.location.href='../ver_libros.php'">
        <div class="card-img-usuario"></div>
        <h3>Libros</h3>
      </div>

      <div class="card" onclick="window.location.href='../penalidades.php'">
        <div class="card-img-penalidades"></div>
        <h3>Penalidades</h3>
      </div>

      <div class="card" onclick="window.location.href='../mis_reservas.php'">
        <div class="card-img-reservas"></div>
        <h3>Reservas</h3>
      </div>

      <div class="card" onclick="window.location.href='../prestamos.php'">
        <div class="card-img-prestamos"></div>
        <h3>Préstamos</h3>
      </div>
    </div>
  </div>

  <script>
    function toggleMenu() {
      const menu = document.getElementById("userMenu");
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    }
    window.addEventListener("click", function(e) {
      const userDiv = document.querySelector(".user");
      if (!userDiv.contains(e.target)) {
        document.getElementById("userMenu").style.display = "none";
      }
    });
  </script>
</body>
</html>
