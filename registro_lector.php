<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="javascript/validar.js" defer></script>
</head>
<body>
    <div class="contenedor_registro animado">
        <h2 class="titulo-registro">Registro de Usuario <span class="obligatorio">(obligatorio)</span></h2>
        
        <form name="registro" action="guardar_usuario.php" method="post" autocomplete="off" onsubmit="return validarFormulario()">
            
            <div class="input_container">
                <div class="input_item">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required data-ayuda="Escriba el nombre en minúscula.">
                    <div class="ayuda"></div> 
                </div>
                <div class="input_item">
                    <label for="apellido1">Primer Apellido:</label>
                    <input type="text" name="apellido1" id="apellido1" required data-ayuda="Escriba el primer apellido en minúscula.">
                    <div class="ayuda"></div> 
                </div>
            </div>

            <div class="input_container">
                <div class="input_item">
                    <label for="apellido2">Segundo Apellido:</label>
                    <input type="text" name="apellido2" id="apellido2" required data-ayuda="Escriba el segundo apellido en minúscula.">
                    <div class="ayuda"></div> 
                </div>
                <div class="input_item">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" name="correo" id="correo" required data-ayuda="Debe estar en minúsculas, contener '@' y terminar en '.com'.">
                    <div class="ayuda"></div>
                </div>
            </div>

            <div class="input_container">
                <div class="input_item">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" name="contrasena" id="contrasena" required data-ayuda="Usa letras, números y puntos.">
                    <div class="ayuda"></div>
                </div>
                <div class="input_item">
                    <label for="repetir_contrasena">Confirmar Contraseña:</label>
                    <input type="password" name="repetir_contrasena" id="repetir_contrasena" required data-ayuda="Debe coincidir con la contraseña.">
                    <div class="ayuda"></div>
                </div>
            </div>

            <div class="input_container">
                <div class="input_item">
                    <label for="identificacion">Número de Identificación:</label>
                    <input type="text" name="identificacion" id="identificacion" required data-ayuda="Solo números, mínimo 10 dígitos.">
                    <?php  session_start();
                    if(isset($_SESSION['mensaje_identificacion'])){
                       echo '<p id="error_id" style="color:red">'.$_SESSION['mensaje_identificacion'].'</p>';

                        unset($_SESSION['mensaje_identificacion']);
                    }
                    ?>
                    <div class="ayuda"></div>
                   
                </div>
                <div class="input_item">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" name="telefono" id="telefono" required data-ayuda="Solo 10 numeros( telefono celular).">
                     <?php  
                    if(isset($_SESSION['mensaje_telefono'])){
                       echo '<p id="error_id" style="color:red">'.$_SESSION['mensaje_telefono'].'</p>';

                        unset($_SESSION['mensaje_telefono']);
                    }
                    ?>
                    <div class="ayuda"></div>
                </div>
            </div>

            <input type="submit" value="Registrar">
        </form>
        
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p> 
    </div>
    <script>
        setTimeout(function(){
            const errormensaje = document.getElementById('error_id');
            if(errormensaje){
                errormensaje.style.display = 'none';
            }
        },3000)
 </script>
</body>
</html>
