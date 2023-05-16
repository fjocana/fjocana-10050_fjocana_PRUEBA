<?php

	$username=$_POST['username'];
	$password= $_POST['password'];
    $roles_id=$_POST['roles_id'];
	require("constantes.php");
//la variable  $mysqli viene de connect_db que lo traigo con el require("connect_db.php");
$conexion = mysqli_connect(SERVER, USER, PASS, BD_USER);
$consulta = "SELECT * FROM usuarios WHERE username = '$username'";
$resultado = mysqli_query($conexion, $consulta);
$filas = mysqli_num_rows($resultado);
		if($password==$password){
			if($filas>0){
				echo ' <script language="javascript">alert("Usuario Existente!");</script> ';
			}else{
				
				//require("connect_db.php");
//la variable  $mysqli viene de connect_db que lo traigo con el require("connect_db.php");
				$res="INSERT INTO `usuarios`(`id`, `username`, `password`,`roles_id`) VALUES (NULL,'$username','$password','$roles_id')";
				//echo 'Se ha registrado con exito';
				mysqli_query($conexion, $res);
				echo ' <script language="javascript">alert("Placa registrada con éxito");</script> ';
				header("Refresh: 2; URL=../index.php");
			}
			
		}else{
			echo 'Las contraseñas son incorrectas';
		}

	
?>