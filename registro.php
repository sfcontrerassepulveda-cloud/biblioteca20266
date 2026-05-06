<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="javascript/validar.js" defer></script>
</head>
<body>
    <div class="contenedor animado">
        <h2 class="titulo-registro">Registro de Usuario <span class="obligatorio">(obligatorio)</span></h2>
        
        <form name="registro" action="guardar_usuario.php" method="post" autocomplete="off" onsubmit="return validarFormulario()">

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required data-ayuda="Escriba el nombre en minúscula.">
            <div class="ayuda"></div>

            <label for="apellido1">Primer Apellido:</label>
            <input type="text" name="apellido1" id="apellido1" required data-ayuda="Escriba el primer apellido en minúscula.">
            <div class="ayuda"></div>

            <label for="apellido2">Segundo Apellido:</label>
            <input type="text" name="apellido2" id="apellido2" required data-ayuda="Escriba el segundo apellido en minúscula.">
            <div class="ayuda"></div>

            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" required data-ayuda="Debe estar en minúsculas, contener '@' y terminar en '.com'.">
            <div class="ayuda"></div>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required data-ayuda="Usa letras, números y puntos.">
            <div class="ayuda"></div>

            <label for="repetir_contrasena">Confirmar Contraseña:</label>
            <input type="password" name="repetir_contrasena" id="repetir_contrasena" required data-ayuda="Debe coincidir con la contraseña.">
            <div class="ayuda"></div>

            <label for="identificacion">Número de Identificación:</label>
            <input type="text" name="identificacion" id="identificacion" required data-ayuda="Solo números, sin letras, minimo 7 dijitos.">
            <div class="ayuda"></div>

            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" id="telefono" required data-ayuda="Solo números, entre 7 y 15 dígitos.">
            <div class="ayuda"></div>

            <input type="submit" value="Registrar">
        </form>
    </div>
</body>
</html>
