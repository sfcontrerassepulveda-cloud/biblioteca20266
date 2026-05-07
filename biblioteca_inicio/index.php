<?php
session_start();

// Si no hay sesión, redirigir al login
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    header("Location: /login.php");
    exit();
}

// Nombre del bibliotecario guardado en sesión
$nombrebibliotecario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Bibliotecario";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Biblioteca Web</title>
  <link rel="stylesheet" href="estilos.css" />
  <script defer src="script.js"></script>
  <style>
    body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:#2c2c2c;color:#fff; overflow: hidden;box-sizing: border-box;}
    .sidebar{width:250px;background:linear-gradient(180deg,#1e1e1e,#111);position:fixed;inset:0 auto 0 0;padding:20px;box-shadow:2px 0 10px rgba(0,0,0,.4)}
    .sidebar .title{
      color:#00b4d8; 
      text-align:center;
      margin-bottom:20px;
      font-size:20px;
      letter-spacing:1px;
      background:transparent; /* quitamos el fondo rojo */
    }
    .sidebar img.logo-img{display:block;margin:0 auto 20px;width:120px;border-radius:10px}
    .sidebar nav a{display:block;color:#bbb;text-decoration:none;padding:12px;margin:8px 0;border-radius:6px;font-weight:500;transition:.3s}
    .sidebar nav a:hover{background:#00b4d8;color:#fff;padding-left:18px}

    .main{height: 100vh;display: flex; margin-bottom: 0;}
    .main-content{margin-left: 0px;padding-top: 10px;   min-height: 100vh; margin-bottom: 0; padding-bottom: 0; box-sizing: border-box;}
    .topbar{display:flex;justify-content:space-between;align-items:center;background:linear-gradient(90deg,#0077b6,#00b4d8);padding:15px 25px;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.3);position:relative}
    .topbar h1{margin:0;font-size:22px}
    .user-profile{position:relative}
    .user-profile button{background:none;border:none;color:#fff;font-weight:bold;cursor:pointer;font-size:15px}
    .user-profile button:hover{opacity:.8}

    /* Menú desplegable bonito */
    .dropdown{display:none;position:absolute;right:0;top:100%;background:#1e1e1e;border-radius:10px;box-shadow:0 8px 20px rgba(0,0,0,.5);min-width:220px;overflow:hidden;margin-top:8px;z-index:100}
    .dropdown a{display:block;padding:12px;color:#eee;text-decoration:none;font-weight:500;transition:.3s}
    .dropdown a:hover{background:#00b4d8;color:#fff}
    
    .dashboard{margin-top:30px}
    .cards-wrap{ margin-top: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
      justify-content: center;}
    .card{ background: #1e1e1e;
      border-radius: 15px;
      text-align: center;
      padding: 20px;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);}
    .card:hover{transform:translateY(-8px);box-shadow:0 8px 20px rgba(0,0,0,.6)}
    .card-img{width:100%;height:130px;background:linear-gradient(135deg,#00b4d8,#0077b6);border-radius:10px;margin-bottom:12px}
    .card-img-usuario{width:100%;height:130px;background-image:url('../imagenes/usuario.jpg');border-radius:10px;margin-bottom:12px; background-size: cover; background-position:center;}
    .card-img-libro{width:100%;height:130px;background-image:url('../imagenes/libros.jpg');border-radius:10px;margin-bottom:12px; background-size: cover; background-position:center;}
    .card-img-prestamos{width:100%;height:130px;background-image:url('../imagenes/prestamos.jpg');border-radius:10px;margin-bottom:12px; background-size: cover; background-position:center;}
    .card-img-reservas{width:100%;height:130px;background-image:url('../imagenes/reservas.jpg');border-radius:10px;margin-bottom:12px; background-size: cover; background-position:center;}
    .card-img-penalidades{width:100%;height:130px;background-image:url('../imagenes/penalidades.jpg');border-radius:10px;margin-bottom:12px; background-size: cover; background-position:center;}

    .card p{font-weight:bold;color:#eee;font-size:16px}
    .card-link{display:block;color:inherit;text-decoration:none}

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

    @media (max-width: 980px){.cards-wrap{grid-template-columns: repeat(2, 260px)}}
    @media (max-width: 680px){
      .main-content{margin-left:0;padding:16px}
      .sidebar{position:static;width:auto}
      .cards-wrap{grid-template-columns: 1fr; justify-content:stretch}
      
    }
  </style>
</head>
<body>




  <!-- Contenido -->
  <main class="main-content">
    <header class="topbar">
    <div class="empresa"><img src="./logo.webp" alt="Logo Biblioteca" class="logo" />
    <h2>SISTEMA BIBLIOTECARIO</h2></div>
    
      <div class="user-profile">
        <button onclick="toggleDropdown()">👤 <?php echo htmlspecialchars($nombrebibliotecario);?></button>
        <div id="dropdownMenu" class="dropdown">
          <a href="../logout.php">🚪 Cerrar sesión</a>
          <a href="../actualizar_datos_bibliotecario.php">⚙️ Actualizar datos</a>
          <a href="../olvidar.html">🔑 Actualizar contraseña</a>
        </div>
      </div>
    </header>

    <section class="dashboard">
      <div class="cards-wrap">
        <!-- Fila 1 -->
        <div class="card" onclick="window.location.href='../admin_usuarios.php'">
          <div class="card-img-usuario"></div>
          <p>Usuarios</p>
        </div>

        <a class="card-link" href="../registrar_libro.php">
          <div class="card">
            <div class="card-img-libro"></div>
            <p>Libros</p>
          </div>
        </a>

        <div class="card" onclick="window.location.href='../panel_prestamo.php'">
          <div class="card-img-prestamos"></div>
          <p>Préstamos</p>
        </div>

        <!-- Fila 2 -->
        <div class="card" onclick="window.location.href='../penalidades_bibliotecario.php'">
          <div class="card-img-penalidades"></div>
          <p>Penalidades</p>
        </div>

        <a class="card-link" href="../panel_reservas.php">
          <div class="card">
            <div class="card-img-reservas"></div>
            <p>Reservas</p>
          </div>
        </a>
      </div>
    </section>
  </main>

  <script>
    function toggleDropdown() {
      const menu = document.getElementById('dropdownMenu');
      menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }
    // Cerrar el menú si se hace clic fuera
    document.addEventListener('click', (e) => {
      const menu = document.getElementById('dropdownMenu');
      const profile = document.querySelector('.user-profile');
      if (!profile.contains(e.target)) {
        menu.style.display = 'none';
      }
    });
  </script>
</body>
</html>
