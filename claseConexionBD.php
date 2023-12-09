<?php

require_once 'config_BD.php';
class ConectarBD {
    //Se obtienen los datos para acceder a la base ded datos.
    private $hostname = HOST;
    private $database = BD;
    private $user = USER;
    private $password = PASSWORD;
    private $charset = CHARSET;
    private $conexion;
    //Se conecta a la base de datos.
    function getConexion() {
        try {
            
            $this->conexion = new PDO('mysql:host='.$this->hostname.
                    ';dbname='.$this->database . ';charset='.$this->charset, 
                    $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, 
                                          PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $error) {
            echo "no hay conexion";
           die ("¡ERROR: !".$error->getMessage());          
        }
           
        return $this->conexion;        
    }
    function cerrarConexion() {
        
        $this->conexion = null;
    } 
}
?>

