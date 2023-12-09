<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Escuderia</title>
    <style>
        body {
            background-color: burlywood;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
        form label {
            margin-bottom: 10px;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            margin-bottom: 15px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 250px;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: burlywood;
            color: white;
            cursor: pointer;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            padding: 20px 0;
            text-align: center;
            background-color: burlywood;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
     <br/><br/>
        <div>
            <form action="" method="post" enctype="multipart/form-data" onsubmit="validar()">           
                <label>ID: </label><input type="text" name="ID" id="ID" required/>
                <label>Nombre de la Escuderia: </label><input type="text" name="NombreEscuderia" id="NombreEscuderia" required/><br/>
                <label>Nº de Campeonatos: </label><input type="number" name="Campeonatos" id="Campeonatos" required/><br/>
                <label>Nombre de los Pilotos: </label><input type="text" name="NombrePilotos" id="NombrePilotos" required/><br/>
                <label>Imagen: </label><input type="file" name="ImagenBinaria" id="ImagenBinaria" required/><br/>
                <input type="submit" id="enviar"name="enviar" value="NuevaEscuderia" /><br/><br/> 
            </form>
        </div>
        <script>
        //Se hace una validación para evitar que los datos que se introducen son los deseados. Es ejecutada al enviar el formulario i evita que se añadan datos no deseados.
        function validar() {
            var id = document.getElementById('ID').value;
            var nombreEscuderia = document.getElementById('NombreEscuderia').value;
            var campeonatos = document.getElementById('Campeonatos').value;
            var nombrePilotos = document.getElementById('NombrePilotos').value;
            var imagen = document.getElementById('ImagenBinaria').value;
            var letras = /^[A-Za-z\s,]+$/
            var numeros = /^[0-9]+$/
            if (!isNaN(campeonatos) && campeonatos < 0) {
                alert("El número de campeonatos no puede ser negativo.");
                return false;
            }
            if (!id.match(numeros)) {
                alert("El campo id solo puede contener numeros.");
                return false;
            }
            if (!nombrePilotos.match(letras)) {
                alert("El campo Nombre de los Pilotos solo puede contener letras.");
                return false;
            }
            if (!nombreEscuderia.match(letras)) {
                alert("El campo Nombre de la Escuderia solo puede contener letras.");
                return false;
            }
            return true;
        }
    </script>    
    </body>
</html> 

<?php
    //Se comprueba que los datos han pasado la validación y se recoge la información de la imagen.
    if(isset($_POST['enviar'])){
        $imagen = $_FILES["ImagenBinaria"]["tmp_name"];
        $nombreImagen = $_FILES["ImagenBinaria"]["name"];
        insertar_datos($imagen, $nombreImagen);
    }
    //Se recogen todos los datos del formulario y se añaden a la base de datos además de guardar la imagen en su carpeta.
    function insertar_datos($imagen, $nombreImagen) {
    include_once 'claseConexionBD.php';
    $dir = "img/";
    $BD = new ConectarBD();   
    $conn = $BD->getConexion();
    if (isset($imagen,$nombreImagen)) {
        move_uploaded_file($imagen,$dir.$nombreImagen);
    }
    $stmt = $conn->prepare('INSERT INTO escuderiasf1 (ID, NombreEscuderia, Campeonatos, NombrePilotos, ImagenBinaria) '
              . 'VALUES (:ID, :NombreEscuderia, :Campeonatos, :NombrePilotos, :ImagenBinaria)');
        try {
            $stmt->execute( array( ':ID' => $_POST['ID'],
                                   ':NombreEscuderia' => $_POST['NombreEscuderia'],
                                   ':Campeonatos' => $_POST['Campeonatos'],
                                   ':NombrePilotos' => $_POST['NombrePilotos'],
                                   ':ImagenBinaria' => base64_encode($nombreImagen))
                         );
        }
        catch (PDOException $ex) {
            print "¡Error!: " . $ex->getMessage() . "<br/>";
            die();
        }
        $BD->cerrarConexion(); 
        header('Location: consultaPrincipal.php');
    }
    //Cuando se pulsa el botón de cerrar sesión se finaliza la sesión y se redirige al usuario al login.
    if(isset($_POST['cerrar_sesion'])) {
        session_destroy();
        header('Location: login.php');
    }
?>
</body>
    <footer>
        <form method="post" action="">
            <input type="submit" name="cerrar_sesion" value="Cerrar sesión">
        </form>
    </footer>
</html>