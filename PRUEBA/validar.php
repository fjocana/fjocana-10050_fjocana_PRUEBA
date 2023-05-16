<?php
require_once "constantes.php";
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

//Conectar a la base de datos
$conexion = mysqli_connect(SERVER, USER, PASS, BD_USER);
$consulta = "SELECT * FROM usuarios WHERE username = '$username' and password = '$password'";
$resultado = mysqli_query($conexion, $consulta);

$filas = mysqli_num_rows($resultado); //0 si no coincide, 1 o + si concidio

//var_dump($username);

echo '<br>';
if($filas>0){
    $_SESSION['login'] = $username;
    $row = $resultado->fetch_assoc();
    $_SESSION["TipoUsuario"] = $row['roles_id'];
    if($_SESSION["TipoUsuario"]==1){
    header("location:formRegistro.html");
    }elseif($_SESSION["TipoUsuario"]==2){
        header("location:matricula.php");
    }elseif($_SESSION["TipoUsuario"]==5){
        header("location:admin.html"); 
    }
else{
    header("Refresh: 2; URL= ../index.php");
    echo '<h1 style="color: red">NO SE PUDO INGRESAR INTENTE DE NUEVO</h1>';

}
}
mysqli_free_result($resultado);
mysqli_close($conexion);
?>