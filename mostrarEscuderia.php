<?php
    //Se obtiene el id de la consulta que se desea ver.
    $id = $_GET['id'];
    
    //Se obtien la información fracias al id recogido previamente.
    $consulta = obtener_consulta($id);
    function obtener_consulta($id) {
        include_once 'claseConexionBD.php';
        $BD = new ConectarBD();   
        $conn = $BD->getConexion();
        $stmt = $conn->prepare('SELECT * FROM escuderiasf1 WHERE id = :id');
        $stmt->execute(array(':id' => $id));  
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
        $BD->cerrarConexion();
        return $consulta;   
    }

    //Cuando se pulsa el botón de cerrar sesión se finaliza la sesión y se redirige al usuario al login.
    if(isset($_POST['cerrar_sesion'])) {
        session_destroy();
        header('Location: login.php');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>mostrarEscuderia</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="consulta.css">
    </head>
    <body>
        <!--Se muestra la informacion que está almacenada en la variable $consulta -->
        <a href='consultaPrincipal.php'>Atrás</a>;
        <h3>Escuderia con ID <?php echo $id ?></h3>  
        <p>Nombre de la Escuderia: <?php echo $consulta['NombreEscuderia']; ?></p><br/>
        <p>Campeonatos: <?php echo $consulta['Campeonatos']; ?></p><br/>      
        <p>Nombre de los Pilotos: <?php echo $consulta['NombrePilotos']; ?></p><br/>
        <p>Imagen en binario: <img width="300px" height="300px" src="img/<?php echo base64_decode($consulta['ImagenBinaria'])?>"></p>;<br/>
        
        <footer>
            <form method="post" action="">
                <input type="submit" name="cerrar_sesion" value="Cerrar sesión">
            </form>
        </footer>
    </body>
</html>