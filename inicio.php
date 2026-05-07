<?php
session_start();

// 🔒 Verificación de sesión
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo_usuario'])) {
    echo "Acceso denegado. Debes iniciar sesión.";
    exit;
}

$correo = $_SESSION['correo'] ?? "correo@ejemplo.com";
$tipo_usuario = $_SESSION['tipo_usuario'];
$nombre = $_SESSION['nombre'] ?? "Usuario";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }

    /* 📘 Bibliotecario */
    body.bibliotecario {
      background: linear-gradient(135deg, #1e1e2f, #0d0d0d);
    }

    /* 👤 Lector */
    body.lector {
      background: radial-gradient(circle, #1e1e2f, #141414);
    }

    .card {
      background: rgba(30, 30, 47, 0.95);
      padding: 3rem 2rem;
      border-radius: 25px;
      text-align: center;
      width: 480px;
      opacity: 0;
      animation: fadeIn 1.2s ease forwards;
      position: relative;
      z-index: 2;
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: scale(0.8); }
      100% { opacity: 1; transform: scale(1); }
    }

    .icon {
      font-size: 60px;
      margin-bottom: 15px;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    h1 {
      font-weight: 700;
      margin-bottom: 1rem;
      animation: heartbeat 2s infinite;
    }

    @keyframes heartbeat {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    p { margin: 0.5rem 0; color: #ddd; }
    .highlight { font-weight: 600; }

    .btns {
      margin-top: 2rem;
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    a.boton, button {
      position: relative;
      border: none;
      padding: 12px 24px;
      border-radius: 14px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      overflow: hidden;
      transition: transform 0.3s ease;
      color: #000;
      text-decoration: none;
      display: inline-block;
    }

    a.boton::before, button::before {
      content: "";
      position: absolute;
      top: 0;
      left: -75%;
      width: 50%;
      height: 100%;
      background: rgba(255,255,255,0.4);
      transform: skewX(-25deg);
    }

    a.boton:hover::before, button:hover::before { animation: shine 0.8s forwards; }
    @keyframes shine { 100% { left: 125%; } }
    a.boton:hover, button:hover { transform: scale(1.08); }

    /* 🎨 Colores dinámicos según rol */
    body.bibliotecario .card { box-shadow: 0 0 25px rgba(255, 64, 129, 0.4); }
    body.lector .card { box-shadow: 0 0 25px rgba(0, 229, 255, 0.4); }

    body.bibliotecario .icon { color: #ff4081; text-shadow: 0 0 15px #ff4081; }
    body.lector .icon { color: #00e5ff; text-shadow: 0 0 15px #00e5ff; }

    body.bibliotecario h1 { color: #ff4081; text-shadow: 0 0 20px #ff4081; }
    body.lector h1 { color: #00e5ff; text-shadow: 0 0 20px #00e5ff; }

    body.bibliotecario .highlight { color: #ff4081; }
    body.lector .highlight { color: #00e5ff; }

    body.bibliotecario a.boton, body.bibliotecario button { background: #ff4081; box-shadow: 0 0 20px #ff4081; }
    body.lector a.boton, body.lector button { background: #00e5ff; box-shadow: 0 0 20px #00e5ff; }

    /* Partículas */
    .particle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.6;
      animation: rise 6s linear infinite;
    }

    body.bibliotecario .particle { background: #ff4081; }
    body.lector .particle { background: #00e5ff; }

    @keyframes rise {
      0% { transform: translateY(100vh) scale(0.5); opacity: 0.8; }
      100% { transform: translateY(-10vh) scale(1); opacity: 0; }
    }
  </style>
</head>
<body class="<?php echo $tipo_usuario; ?>">
  <div class="card">
    <div class="icon">
      <?php echo ($tipo_usuario == 'bibliotecario') ? "📚" : "👤"; ?>
    </div>
    <h1>Bienvenido, <?php echo $nombre; ?>!</h1>
    <p><span class="highlight">Correo:</span> <?php echo $correo; ?></p>
    <p><span class="highlight">Rol:</span> <?php echo ucfirst($tipo_usuario); ?></p>
    <p>
      <?php if ($tipo_usuario == 'bibliotecario'): ?>
        <b>Eres bibliotecario.</b> Podrás gestionar libros y lectores.
      <?php else: ?>
        <b>Eres lector.</b> Próximamente podrás ver tus opciones y consultas.
      <?php endif; ?>
    </p>
    <div class="btns">
      <a class="boton" href="logout.php">Cerrar sesión</a> 
      <?php if ($tipo_usuario == 'bibliotecario'): ?>
        <button onclick="window.location.href='./'">Bibliotecario</button>
      <?php elseif ($tipo_usuario == 'lector'): ?>
        <button onclick="window.location.href='./lector/'">Lector</button>
      <?php endif; ?>
    </div>
  </div>

  <script>
    for(let i=0; i<40; i++){
      let particle = document.createElement("div");
      particle.classList.add("particle");
      document.body.appendChild(particle);
      let size = Math.random()*6+4;
      particle.style.width = `${size}px`;
      particle.style.height = `${size}px`;
      particle.style.left = Math.random()*100 + "vw";
      particle.style.animationDuration = (Math.random()*5+3) + "s";
      particle.style.animationDelay = Math.random()*5 + "s";
    }
  </script>
</body>
</html>
