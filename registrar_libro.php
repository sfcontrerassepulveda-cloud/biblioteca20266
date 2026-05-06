<?php
session_start();

// Verificar si el usuario es bibliotecario
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 'bibliotecario') {
    echo "Acceso denegado. Solo bibliotecarios pueden registrar libros.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Libro</title>
    <link rel="stylesheet" href="css/estilos_registro.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background: src= 'laragon\www\biblioteca\imagenes/biblio.jpg';
    background-size: cover; /* Hace que la imagen se adapte a la pantalla */
    margin: 0;
    padding: 0;
}

        .contenedor {
            width: 500px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f9f9f9;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], 
        input[type="number"], 
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .ubicacion {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 15px;
        }

        .ubicacion select {
            flex: 1;
            min-width: 80px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 10px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Registrar un nuevo libro</h2>
        <form action="guardar_libro.php" method="post" autocomplete="off">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" required>

            <label for="anio">Año:</label>
            <input type="number" id="anio" name="año">

            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Seleccione una categoría</option>

              <!-- 000 -->
<optgroup label="000 Generalidades">
  <option value="010 Bibliografía">010 Bibliografía</option>
  <option value="020 Bibliotecología y ciencias de la información">020 Bibliotecología y ciencias de la información</option>
  <option value="030 Enciclopedias generales">030 Enciclopedias generales</option>
  <option value="040">040</option>
  <option value="050 Publicaciones en serie">050 Publicaciones en serie</option>
  <option value="060 Organizaciones y museografía">060 Organizaciones y museografía</option>
  <option value="070 Periodismo, editoriales, diarios">070 Periodismo, editoriales, diarios</option>
  <option value="080 Colecciones generales">080 Colecciones generales</option>
  <option value="090 Manuscritos y libros raros">090 Manuscritos y libros raros</option>
</optgroup>

<!-- 100 -->
<optgroup label="100 Filosofía y psicología">
  <option value="110 Metafísica">110 Metafísica</option>
  <option value="120 Conocimiento, causa, fin, hombre">120 Conocimiento, causa, fin, hombre</option>
  <option value="130 Parapsicología, ocultismo, fenómenos paranormales">130 Parapsicología, ocultismo, fenómenos paranormales</option>
  <option value="140 Escuelas filosóficas específicas">140 Escuelas filosóficas específicas</option>
  <option value="150 Psicología">150 Psicología</option>
  <option value="160 Lógica">160 Lógica</option>
  <option value="170 Ética (filosofía moral)">170 Ética (filosofía moral)</option>
  <option value="180 Filosofía antigua, medieval, oriental">180 Filosofía antigua, medieval, oriental</option>
  <option value="190 Filosofía moderna occidental">190 Filosofía moderna occidental</option>
</optgroup>

<!-- 200 -->
<optgroup label="200 Religión">
  <option value="210 Filosofía y teoría de la religión">210 Filosofía y teoría de la religión</option>
  <option value="220 Biblia">220 Biblia</option>
  <option value="230 Teología cristiana">230 Teología cristiana</option>
  <option value="240 Moral y prácticas cristianas">240 Moral y prácticas cristianas</option>
  <option value="250 Iglesia local y órdenes religiosas">250 Iglesia local y órdenes religiosas</option>
  <option value="260 Teología social y eclesiástica">260 Teología social y eclesiástica</option>
  <option value="270 Historia y geografía de la iglesia cristiana">270 Historia y geografía de la iglesia cristiana</option>
  <option value="280 Credos y sectas de la iglesia cristiana">280 Credos y sectas de la iglesia cristiana</option>
  <option value="290 Otras religiones">290 Otras religiones</option>
</optgroup>

<!-- 300 -->
<optgroup label="300 Ciencias sociales">
  <option value="310 Estadística">310 Estadística</option>
  <option value="320 Ciencia política">320 Ciencia política</option>
  <option value="330 Economía">330 Economía</option>
  <option value="340 Derecho">340 Derecho</option>
  <option value="350 Administración pública y ciencia militar">350 Administración pública y ciencia militar</option>
  <option value="360 Problemas y servicios sociales">360 Problemas y servicios sociales</option>
  <option value="370 Educación">370 Educación</option>
  <option value="380 Comercio, comunicaciones y transporte">380 Comercio, comunicaciones y transporte</option>
  <option value="390 Costumbres y folklore">390 Costumbres y folklore</option>
</optgroup>

<!-- 400 -->
<optgroup label="400 Lenguas">
  <option value="410 Lingüística">410 Lingüística</option>
  <option value="420 Inglés e inglés antiguo">420 Inglés e inglés antiguo</option>
  <option value="430 Lenguas germánicas; alemán">430 Lenguas germánicas; alemán</option>
  <option value="440 Lenguas romances; francés">440 Lenguas romances; francés</option>
  <option value="450 Italiano, rumano, rético">450 Italiano, rumano, rético</option>
  <option value="460 Español y portugués">460 Español y portugués</option>
  <option value="470 Lenguas itálicas; latín">470 Lenguas itálicas; latín</option>
  <option value="480 Lenguas helénicas; griego clásico">480 Lenguas helénicas; griego clásico</option>
  <option value="490 Otras lenguas">490 Otras lenguas</option>
</optgroup>

<!-- 500 -->
<optgroup label="500 Matemáticas y ciencias naturales">
  <option value="510 Matemáticas">510 Matemáticas</option>
  <option value="520 Astronomía y ciencias afines">520 Astronomía y ciencias afines</option>
  <option value="530 Física">530 Física</option>
  <option value="540 Química y ciencias afines">540 Química y ciencias afines</option>
  <option value="550 Geociencias">550 Geociencias</option>
  <option value="560 Paleontología. paleozoología">560 Paleontología. paleozoología</option>
  <option value="570 Ciencias biológicas">570 Ciencias biológicas</option>
  <option value="580 Ciencias botánicas">580 Ciencias botánicas</option>
  <option value="590 Ciencias zoológicas">590 Ciencias zoológicas</option>
</optgroup>

<!-- 600 -->
<optgroup label="600 Tecnología y ciencias aplicadas">
  <option value="610 Ciencias médicas">610 Ciencias médicas</option>
  <option value="620 Ingeniería y operaciones afines">620 Ingeniería y operaciones afines</option>
  <option value="630 Agricultura y tecnologías afines">630 Agricultura y tecnologías afines</option>
  <option value="640 Economía doméstica">640 Economía doméstica</option>
  <option value="650 Servicios administrativos empresariales">650 Servicios administrativos empresariales</option>
  <option value="660 Química industrial">660 Química industrial</option>
  <option value="670 Manufacturas">670 Manufacturas</option>
  <option value="680 Manufacturas varias">680 Manufacturas varias</option>
  <option value="690 Construcciones">690 Construcciones</option>
</optgroup>

<!-- 700 -->
<optgroup label="700 Artes">
  <option value="710 Urbanismo y arquitectura del paisaje">710 Urbanismo y arquitectura del paisaje</option>
  <option value="720 Arquitectura">720 Arquitectura</option>
  <option value="730 Artes plásticas; escultura">730 Artes plásticas; escultura</option>
  <option value="740 Dibujo, artes decorativas">740 Dibujo, artes decorativas</option>
  <option value="750 Pintura y pinturas">750 Pintura y pinturas</option>
  <option value="760 Artes gráficas; grabados">760 Artes gráficas; grabados</option>
  <option value="770 Fotografía y fotografías">770 Fotografía y fotografías</option>
  <option value="780 Música">780 Música</option>
  <option value="790 Entretenimiento">790 Entretenimiento</option>
</optgroup>

<!-- 800 -->
<optgroup label="800 Literatura">nn                                                                                                                                                                                        
  <option value="810 Literatura americana en inglés">810 Literatura americana en inglés</option>
  <option value="820 Literatura inglesa e inglesa antigua">820 Literatura inglesa e inglesa antigua</option>
  <option value="830 Literaturas germánicas">830 Literaturas germánicas</option>
  <option value="840 Literaturas de las lenguas romances">840 Literaturas de las lenguas romances</option>
  <option value="850 Literaturas italiana, rumana">850 Literaturas italiana, rumana</option>
  <option value="860 Literaturas española y portuguesa">860 Literaturas española y portuguesa</option>
  <option value="870 Literaturas de las lenguas itálicas">870 Literaturas de las lenguas itálicas</option>
  <option value="880 Literaturas de las lenguas helénicas">880 Literaturas de las lenguas helénicas</option>
  <option value="890 Literaturas de otras lenguas">890 Literaturas de otras lenguas</option>
</optgroup>

<!-- 900 -->
<optgroup label="900 Historia y geografía">
  <option value="910 Geografía; viajes">910 Geografía; viajes</option>
  <option value="920 Biografía y genealogía">920 Biografía y genealogía</option>
  <option value="930 Historia del mundo antiguo">930 Historia del mundo antiguo</option>
  <option value="940 Historia de Europa">940 Historia de Europa</option>
  <option value="950 Historia de Asia">950 Historia de Asia</option>
  <option value="960 Historia de África">960 Historia de África</option>
  <option value="970 Historia de América del Norte">970 Historia de América del Norte</option>
  <option value="980 Historia de América del Sur">980 Historia de América del Sur</option>
  <option value="990 Historia de otras regiones">990 Historia de otras regiones</option>


                </optgroup>
            </select>

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn">

            <label>Ubicación:</label>
            <div class="ubicacion">
                <select id="piso" name="piso" required>
                    <option value="">Piso</option>
                    <option value="1">Piso 1</option>
                    <option value="2">Piso 2</option>
                    <option value="3">Piso 3</option>
                </select>

                <select id="estante" name="estante" required>
                    <option value="">Estante</option>
                    <option value="A">Estante A</option>
                    <option value="B">Estante B</option>
                    <option value="C">Estante C</option>
                </select>

                <select id="columna" name="columna" required>
                    <option value="">Columna</option>
                    <option value="1">Columna 1</option>
                    <option value="2">Columna 2</option>
                    <option value="3">Columna 3</option>
                </select>

                <select id="fila" name="fila" required>
                    <option value="">Fila</option>
                    <option value="1">Fila 1</option>
                    <option value="2">Fila 2</option>
                    <option value="3">Fila 3</option>
                </select>
            </div>

            <input type="submit" value="Guardar Libro">
        </form>

        <p><a href="http://localhost/biblioteca/biblioteca_inicio/">Volver al inicio</a></p>
        <p><a href="./ver_libros.php">Ver lista de libros</a></p>
        <p><a href="./inventario.php">Ir a Inventarios</a></p>
    </div>
</body>
</html>
