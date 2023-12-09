<html>
<head>
    <meta charset="UTF-8">
    <title>Escuderias F1</title>
    <link rel='stylesheet' type='text/css' href='consulta.css' />
</head>
<body>
    <p> Usuario: <?php session_start(); echo $_SESSION['usuario']; ?> Rol: <?php echo $_SESSION['rol']; ?></p>
    <h3>Escuderias F1</h3>
    <!--Se comprueba el rol del usuario para saber si puede añadir contenido o no -->
    <?php
        if($_SESSION['rol'] == 'administrador') {
            echo "<a href=\"añadirEscuderia.php\">Nueva Escudería</a><br/>";
        }
    ?>
    <table border=1>
        <tr>
            <th>ID</th>
            <th>Nombre de la Escuderia</th>
            <th>Numero de campeonatos</th>
            <th>NombrePilotos</th>
            <th>Imagen</th>
            <th>Imagen Binaria</th>
            <th colspan="2">Acciones</th>
        </tr>
        <?php
            //Cuando se pulsa el botón de cerrar sesión se finaliza la sesión y se redirige al usuario al login.
            if(isset($_POST['cerrar_sesion'])) {
                session_destroy();
                header('Location: login.php');
            }
            //Se conecta con la base de datos y se muestra la información en forma de tabla añadiendo el botón visualizar que permite ver la información de una única fila en una nueva página.            
            try {
                $conn = new PDO('mysql:host=localhost;dbname=practica1;charset=utf8', 'adrian', 'adrian');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare('SELECT * FROM escuderiasf1 order by ID');
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $stmt->execute();
                while ($escuderia = $stmt->fetch()) {
                    $id = $escuderia['ID'];
                    echo "<tr>";
                    echo "<td>".$escuderia['ID']."</td><td>".$escuderia['NombreEscuderia']."</td><td>".$escuderia['Campeonatos']. "</td><td>".$escuderia['NombrePilotos'].
                             "</td><td>". '<img width="90px" height="90px" src="img/' . base64_decode($escuderia['ImagenBinaria']).
                             '"></td><td>'. '<img width="90px" height="90px" src="img/'. base64_decode($escuderia['ImagenBinaria']).'"></td>'.
                             "<td><a href='mostrarEscuderia.php?id=". $id . "' class='btn btn-primary'>Visualizar</td>";
                    echo "</tr>";
                }
            } catch (PDOException $ex) {
                print "¡Error!: " . $ex->getMessage() . "<br/>";
                exit;
            }
        ?>
    </table>
</body>
    <footer>
        <form method="post" action="">
            <input type="submit" name="cerrar_sesion" value="Cerrar sesión">
        </form>
    </footer>
</html>
