<?php
class agencia{
	private $id;
	private $descripcion;
	private $direccion;
    private $telefono;

	
	function __construct($cn){
		$this->con = $cn;
	}
		
		
//*********************** 3.1 METODO update_marca() **************************************************	
	
	public function update_agencia(){
		$this->id = $_POST['id'];
		$this->descripcion = $_POST['descripcion'];
		$this->direccion = $_POST['direccion'];
        $this->telefono = $_POST['telefono'];
		
		$sql = "UPDATE agencia SET descripcion='$this->descripcion',
									direccion='$this->direccion',
                                    telefono='$this->telefono'
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

	public function save_agencia(){
		
		$this->descripcion = $_POST['descripcion'];
		$this->direccion = $_POST['direccion'];
        $this->telefono = $_POST['telefono'];
	
				
		$sql = "INSERT INTO agencia VALUES(NULL,
											'$this->descripcion',
											'$this->direccion',
                                            '$this->telefono');";
		echo $sql;
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
					// OPCION PARA GRABAR UN NUEVOmarca (id=0)
					$html .= '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
				
				}else{
					// OPCION PARA MODIFICAR UN marca EXISTENTE
					$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
				}
			
			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
	
	
//************************************* PARTE II ****************************************************	

	public function get_form($id=NULL){
		
		if($id == NULL){
			$this->descripcion = NULL;
			$this->direccion;
            $this->telefono;
			
			$op = "new";
		}else{

			$sql = "SELECT * FROM agencia WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la agencia con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   
			
                /*// -- //
				echo "<br>TUPLA <br>";
				echo "<pre>";
					print_r($row);
				echo "</pre>";
			*/
			$this->descripcion = $row['descripcion'];
			$this->direccion = $row['direccion'];
            $this->telefono= $row['telefono'];
			
			$op = "update";
			}
		}
		
		
		$html = '
		<form name="agencia" method="POST" action="agencia.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table class="table table-dark">
				<tr>
					<th colspan="2">DATOS AGENCIA</th>
				</tr>
				<tr>
					<td>Descripcion:</td>
					<td><input type="text" size="6" name="descripcion" value="' . $this->descripcion . '" required></td>
				</tr>
				<tr>
					<td>Direccion:</td>
					<td><input type="text" size="6" name="direccion" value="' . $this->direccion . '" required></td>
				</tr>
				<tr>
					<td>Telefono:</td>
					<td><input type="text" size="6" name="telefono" value="' . $this->telefono . '" required></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table>';
		return $html;
	}
	
	

	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<table class="table table-dark">
			<tr>
				<th colspan="8"><center>LISTA DE AGENCIAS</center></th>
			</tr>
			<tr>
				<th colspan="8"><a href="agencia.php?f=' . $d_new_final . '"><center>Nuevo</center></a></th>
			</tr>
			<tr>
				<th>Ubicacion</th>
				<th>Direccion</th>
                <th>Telefono</th>
				<th colspan="3"><center>Acciones</center></th>
			</tr>';
		$sql = "SELECT id, descripcion , direccion, telefono  FROM agencia ;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
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
					<td>' . $row['direccion'] . '</td>
                    <td>' . $row['telefono'] . '</td>
                   
					
					<td><a href="agencia.php?f=' . $d_del_final . '">Borrar</a></td>
					<td><a href="agencia.php?f=' . $d_act_final . '">Actualizar</a></td>
					<td><a href="agencia.php?f=' . $d_det_final . '">Detalle</a></td>
				</tr>';
		}
		$html .= '  
		</table>
		<a align="center" href="index.html">Regresar </a>';
		
		return $html;
		
	}
	
	
	public function get_detail_agencia($id){
		$sql = "SELECT descripcion , direccion, telefono  
                FROM agencia
				WHERE id=$id ";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;

        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el marca con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar la agencia con id= ".$id;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<table class="table table-dark">
					<tr>
						<th colspan="2">DATOS DE LA AGENCIA</th>
					</tr>
					<tr>
						<td>Descripcion: </td>
						<td>'. $row['descripcion'] .'</td>
					</tr>
					<tr>
						<td>Direccion: </td>
						<td>'. $row['direccion'] .'</td>
					</tr>
					<tr>
						<td>Telefono: </td>
						<td>'. $row['telefono'] .'</td>
					</tr>
					<tr>
						<th colspan="2"><a href="agencia.php">Regresar</a></th>
					</tr>																						
				</table>';
				
				return $html;
		}
	}
	
	
	public function delete_agencia($id){
		$sql = "DELETE FROM agencia WHERE id=$id;";
			if($this->con->query($sql)){
			echo $this->_message_ok("eliminar");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}
	
//*************************************************************************

	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	
//*************************************************************************	
	
	private function _message_error($tipo){
		$html = '
		<table class="table table-dark">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="agencia.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table class="table table-dark">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="agencia.php>Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

