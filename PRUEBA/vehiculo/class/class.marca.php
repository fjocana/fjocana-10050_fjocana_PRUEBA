<?php
class marca{
	private $id;
	private $descripcion;
	private $foto;
	private $pais;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		
//*********************** 3.1 METODO update_marca() **************************************************	
	
	public function update_marca(){
		$this->id = $_POST['id'];
			
		$this->descripcion = $_POST['descripcion'];
		$this->pais = $_POST['pais'];

		$sql = "UPDATE marca SET descripcion='$this->descripcion',
						pais='$this->pais'
						WHERE id=$this->id;";
		echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}								
										
	}
	
//*********************** 3.2 METODO save_marca() **************************************************	

	public function save_marca(){
		
		$this->descripcion = $_POST['descripcion'];
		$this->pais = $_POST['pais'];
		
		$this->foto = $this->_get_name_file($_FILES['foto']['name'],12);
		
		$path = "img/" . $this->foto;
		//print_r($_FILES);
		//exit;
		if(!move_uploaded_file($_FILES['foto']['tmp_name'],$path)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
		
		$sql = "INSERT INTO marca VALUES (NULL,'$this->descripcion', '$this->pais', '$this->foto');";
		//echo $sql;
		//exit;
		if($this->con->query($sql)){
			echo $this->_message_ok("guardó");
		}else{
			echo $this->_message_error("guardar");
		}								
	}

//*********************** 3.3 METODO _get_name_File() **************************************************	
	
	private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}
	
//*************************************** PARTE I ************************************************************
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto){
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($i == $defecto)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto){
		$html = '
		<table border=0 align="left">';
		
		//CODIGO NECESARIO EN CASO QUE EL USUARIO NO SE ESCOJA UNA OPCION
		
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				
				if($defecto == NULL){
					// OPCION PARA GRABAR UN NUEVO VEHICULO (id=0)
					$html .= '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
				
				}else{
					// OPCION PARA MODIFICAR UN VEHICULO EXISTENTE
					$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
				}
			
			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
	
//************************************* PARTE II ****************************************************	

	public function get_form_marca($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->descripcion = NULL;
			$this->pais = NULL;
			$this->foto = NULL;
			
			$flag = 'enabled';
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM marca WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){
                $mensaje = "tratar de actualizar la marca con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				
				/*echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";*/
			
		
             // ATRIBUTOS DE LA CLASE MARCA   
                $this->descripcion = $row['descripcion'];
                $this->pais = $row['pais'];
                $this->foto = $row['foto'];
				
                $flag = "disabled";
				//$flag = "enabled";
                $op = "act"; 
            }
	}
        
	if($bandera){		
		$html = '
		
		<form class=" " name="Form_marca" method="POST" action="marca.php" enctype="multipart/form-data" >
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			
			<table class="table table-hover" style="margin-top:5%; background-color:#f1fdf3;" border=2 align="center">
				<tr>
					<th class="text-center" style="color:#fff; background-color:#023354;" colspan="5"><b>DATOS MARCA</b></th>
				</tr>
				<tr>
					<td style="color:#fff; background-color:#3e769c"><b>Descripcion:</b></td>
					
					<td><input for="floatingTextInput1" class="col-12" type="text" name="descripcion" value="' . $this->descripcion . '"></td>
				</tr>
				<tr>
					<td style="color:#fff; background-color:#3e769c"><b>Pais:</b></td>
					<td> '. $this->_get_combo_db("marca","pais","pais","pais",$this->pais) . '</td>
				</tr>
				<tr>
					<td style="color:#fff; background-color:#3e769c"><b>Foto:</b></td>
					<td><input type="file" name="foto" class="col-12"' . $flag . '></td>
				</tr>
				<tr>
					<th class="text-center" style="color:#fff; background-color:#023354;" colspan="5"><input type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>	
				<tr>
					<th class="text-center" style="color:#f7f5f5; background-color:#023354;" colspan="8"><a href="../index.html" class="text-center btn" style="color:#f7f5f5; background-color:#6d96b3" ><b>Regresar</b></a></th>
				</tr>											
			</table>';
		return $html;
		}
	}
	
	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<div class="container" style="margin-top:30px;">
		<table class="table table-hover" style="background-color:#f1fdf3;" border="1" align="center">
			<tr>
				<th class="text-center" style="color:#f7f5f5; background-color:#023354;" colspan="8">Lista de Marcas</th>
			</tr>
			<tr>
				<th class="text-center" colspan="8" style="color:#f7f5f5; background-color:#175075"><a href="marcas.php?d=' . $d_new_final . '"class="text-center btn" style="color:#f7f5f5; background-color:#6d96b3" ><b>Nuevo</b></a></th>
			</tr>
			<tr>
				<th class="text-center" style="color:#f7f5f5; background-color:#3e769c">Marca</th>
				<th class="text-center" style="color:#f7f5f5; background-color:#3e769c">Pais</th>
				<th class="text-center" colspan="3" style="color:#f7f5f5; background-color:#3e769c">Acciones</th>
			</tr>';
		$sql = "SELECT id, descripcion, pais, foto from marca;";	

		$res = $this->con->query($sql);
		// Sin codificar <td><a href="marcas.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		
		$num = $res->num_rows;
    if($num != 0){
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['id'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['id'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['id'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['descripcion'] . '</td>
					<td>' . $row['pais'] . '</td>
					<td class="text-center"><a href="marcas.php?d=' . $d_del_final . '"class="text-center btn" style="color:#000000; background-color:#6d96b3" ><b>Borrar</a></td>
					<td class="text-center"><a href="marcas.php?d=' . $d_act_final . '"class="text-center btn" style="color:#000000; background-color:#6d96b3" ><b>Actualizar</a></td>
					<td class="text-center"><a href="marcas.php?d=' . $d_det_final . '"class="text-center btn" style="color:#000000; background-color:#6d96b3" ><b>Detalle</a></td>
				</tr>';
		}
		$html .= '<tr>
					<th class="text-center" style="color:#f7f5f5; background-color:#023354;" colspan="8"><a href="../index.html" class="text-center btn" style="color:#f7f5f5; background-color:#6d96b3" ><b>Regresar</b></a></th>
				</tr>
				</table>';
	}else{
		$mensaje = "Tabla Marcas" . "<br>";
		echo $this->_message_BD_Vacia($mensaje);
		echo "<br><br><br>";
	}
	$html .= '</table>';
		return $html;
	}
	
	public function get_detail_marca($id){
		$sql = "SELECT * FROM marca WHERE marca.id = $id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el marca con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el marca con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<table class="table table-hover" style="background-color:#f1fdf3;" border="1" align="center">
					<tr>
						<th class="text-center" style="color:#fff; background-color:#023354;" colspan="5">DATOS DE LAS MARCAS</th>
					</tr>
					<tr>
						<td style="color:#fff; background-color:#3e769c">Marca: </td>
						<td>'. $row['descripcion'] .'</td>
					</tr>
					<tr>
						<td style="color:#fff; background-color:#3e769c">Pais: </td>
						<th>$'. $row['pais'] .' </th>
					</tr>
					<tr>
						<th class="text-center" colspan="6" style="color:#f7f5f5; background-color:#7d92a1" ><img src="img/' . $row['foto'] . '" width="300px"/></th>
					</tr>	
					<tr>
						<th class="text-center" colspan="8"><a href="../index.html" class="text-center btn" style="color:#f7f5f5; background-color:#6d96b3" ><b>Regresar</b></a></th>
					</tr>
				</table>';
				
				return $html;
		}
	}
	
	//Delete
	//*****************************************
	
	public function delete_marca($id){
		$sql = "DELETE FROM marca WHERE id=$id;";
			if($this->con->query($sql)){
			echo $this->_message_ok("eliminar");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}
	
	
//*************************************************************************	
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th class="text-center" colspan="8"><a href="../index.html" class="text-center btn" style="color:#f7f5f5; background-color:#6d96b3" ><b>Regresar</b></a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th class="text-center" colspan="8"><a href="../index.html" class="text-center btn" style="color:#f7f5f5; background-color:#6d96b3" ><b>Regresar</b></a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

