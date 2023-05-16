
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Marcas</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
require_once("constantes.php");
include_once("class/class.matriculacion.php");

$cn = conectar();
$m = new Matricula($cn);

echo $m->get_form();
if(isset($_POST['Guardar'])){
    $m->save();
    unset($_POST);
}


//*******************************************************
function conectar(){
    //echo "<br> CONEXION A LA BASE DE DATOS<br>";
    $c = new mysqli(SERVER,USER,PASS,BD_MATER);

    if($c->connect_errno) {
        die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
    }else{
        //echo "La conexión tuvo éxito .......<br><br>";
    }

    $c->set_charset("utf8");
    return $c;
}
//**********************************************************


?>
</body>
</html>
