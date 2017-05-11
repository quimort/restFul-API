<?php
/**
 * Clase que envuelve una instancia de la clase PDO
 * para el manejo de la base de modelos
 */
include 'login_mysql.php';
class ConexionBD
{
    private $myconect;
    function connect(){
        $con = mysqli_connect(NOMBRE_HOST,USUARIO,CONTRASENA,BASE_DE_DATOS);
        if(!$con){
            die('Could not connect to database!');
        }else{
            $this->myconect=$con;
        }
        return $this->myconect;
    }
    function close($conn){
        mysqli_close($conn);
    }
    function generateApiKey() {
        return md5(uniqid(rand(), true));
    }
    function cryptpass($password){
        $cryptedpassword=crypt($password,'$1$rasmusle$');
        return $cryptedpassword;
    }
    function query($query){
        $con=$this->connect();
        $result=mysqli_query($con,$query);
        $this->close($con);
        return $result;
    }
    function insertuser($name,$password,$email){
        $con=$this->connect();
        $respuesta=array();
        $validacionnombre=$this->validator($name);
        $validacionemail=$this->validaremail($email);
        if($validacionnombre==0 && $validacionemail==0){
            $api_key=$this->generateApiKey();
            $password=$this->cryptpass($password);
            $query=" INSERT INTO users (`name`,`email`,`password_hash`,`api_key`) VALUES('".$name."','".$email."','".$password."','".$api_key."') ";
            $resultado=mysqli_query($con,$query);
            $respuesta=$resultado;
        }
        if($validacionnombre!==0){
            $respuesta=""
        }
        $this->close($con);
        return $respuesta;
    }
    function validarnombre($name){
        $connectio=$this->connect();
        $query ="SELECT * FROM users WHERE name='".$name."'";
        mysqli_query($connectio,$query);
        $numrows=mysqli_affected_rows($connectio);
        $this->close($connectio);
        return $numrows;
    }
    function validaremail($email){
        $connectio=$this->connect();
        $query="SELECT * FROM users WHERE email='".$email."'";
        mysqli_query($connectio,$query);
        $resultrows=mysqli_num_rows($connectio);
        $this->close($connectio);
        return $resultrows;
    }
}
?>