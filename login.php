<?php

session_start();

//Declaro las variables a usar en el html para evitar posibles errores.
$usuario ='';
$clave ='';
$errorUsu='';
$errorCont='';
$errorTodos='';

if (isset($_POST['entrar']) ) { 
   $usuario = $_POST['usuario'];
   $clave = $_POST['clave']; 
   
   //Se evita que existan datos de otras ejecuciones.
   $errorUsu='';
   $errorCont='';
   $errorTodos='';
    
   if ( $usuario == '' ) {
      $errorUsu= 'Debe introducir el usuario <br>'; 
   }
   if ( $clave == '' ) {
      $errorCont= 'Debe introducir la contraseña <br>'; 
   }

	//Se comprueba que los datos han sido introducidos y si son correctos. Después se crean el usuario, clave y rol como variables de sesión.
   if ( $errorUsu == '' &&  $errorCont == '' ) {
        $fila = verificar_usuario($usuario, $clave);         
        if ( $fila != false ) {  
           $_SESSION['usuario'] = $fila['usuario'];
           $_SESSION['contrasenia'] = $fila['contrasenia'];  
           $_SESSION['rol'] = $fila['rol'];  
           header('Location: consultaPrincipal.php');
        } else {
           $errorTodos= 'Usuario/Contraseña no válidos <br/>';  
        }
   }  

}

//Comprueba la veracidad del usuario y su clave y almacena la información en la variable $fila.
function verificar_usuario($usuario, $clave) {
    include_once 'claseConexionBD.php';  
    $BD = new ConectarBD();   
    $conn = $BD->getConexion();    
    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE usuario=:usuario and contrasenia=:clave');
    $stmt->bindValue(':usuario', $usuario); 
    $stmt->bindValue(':clave', $clave); 
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $fila = $stmt->fetch();
    $stmt->closeCursor();
    $BD->cerrarConexion();
    return $fila; 
} 
?>

<html>
<head><meta charset="UTF-8"><title>Login</title>
<style>
   body{
      background-color: burlywood;
   }
   form{
      display:flex;
      align-self: center;
      justify-content: center;
      background-color: burlywood;
   }
   h1{
      display:flex;
      align-self: center;
      justify-content: center;
      background-color: burlywood;
   }
</style></head>
<body>
   <h1>Login</h1>
   <form action="" method="POST">
	      <label>Usuario: <input type="text" name="usuario" value="<?php echo $usuario; ?>"></label><br/>
	      <label>Contraseña: <input type="password" name="clave" value="<?php echo $clave; ?>"></label><br/>
         <span style="color:red;"><?php echo $errorUsu; ?>
	                               <?php echo $errorCont; ?>
                                  <?php echo $errorTodos; ?>
         </span>	
	      <br/>
	      <input type="submit" name="entrar" value="Entrar">
   </form>    
</body>
</html>