<!DOCTYPE html>
<html>
<head>
    <title>EXAMEN</title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<?php
require_once("constantes.php");
include_once("class/class.matriculacion.php");

$cn = conectar();
$m = new Matricula($cn);
$sql = 'SELECT m.id as id, m.fecha as fecha, v.placa as placa, t.descripcion as marca, a.descripcion as agencia,m.anio,v.avaluo as avaluo,v.anio as anio_a
from matricula m
         inner join vehiculo v on m.vehiculo = v.id
         inner join marca t on t.id = v.marca
         inner join agencia a on m.agencia = a.id';
$matriculados = $cn->query($sql);
?>
    <div class="container col-9 mt-4">
    <table class="table table-dark" >
        <tr>
            <th class="bg-warning" colspan="8"><center>LISTA DE VEHICULOS</center</th>
        </tr>
        <tr>
            <th>Id</th>
            <th>Fecha</th>
            <th>Placa</th>
            <th>Marca</th>
            <th>Agencia</th>
            <th>Año Matricula</th>
            <th>Valor Total</th>
        </tr>
 <?php while($matricula = $matriculados->fetch_object()): ?>
    <tr>
        <td><?=$matricula->id?></td>
        <td><?=$matricula->fecha?></td>
        <td><?=$matricula->placa?></td>
        <td><?=$matricula->marca?></td>
        <td><?=$matricula->agencia?></td>
        <td><?=$matricula->anio?></td>
        <td><?=$m->_calculo_matricula($matricula->avaluo,$matricula->anio_a)?></td>
    </tr>
<?php endwhile;?>
        <tr>
            <th class="bg-info" colspan="8"><a href="index.html">Regresar</a></th>
        </tr>
    </table>
    </div>
<?php
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
?>
</body>
</html>

