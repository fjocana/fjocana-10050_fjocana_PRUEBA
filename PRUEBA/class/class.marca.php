<?php
    class marca{
        private $id;
        private $descripcion;
        private $pais;
        private $con;

        function __construct($cn){
            $this->con = $cn;
        }

        public function update_marca(){
            $this->id = $_POST['id'];
            $this->descripcion = $_POST['descripcion'];
            $this->pais = $_POST['pais'];

            $sql = "UPDATE marca SET descripcion = '$this->descripcion',
                                        pais = '$this->pais'
                                        WHERE id=$this->id";
//            echo $sql;
//            exit;
            if($this->con->query($sql)){
                echo $this->_message_ok("modificó");
            }else{
                echo $this->_message_error("al modificar");
            }
        }

        public function save_marca(){
            $this->descripcion = $_POST['descripcion'];
            $this->pais = $_POST['pais'];

            $sql = "INSERT INTO marca VALUES(NULL,
                                                '$this->descripcion',
                                                '$this->pais');";

            //echo $sql;
            //exit;

            if($this->con->query($sql)){
                echo $this->_message_ok("guardó");
            }else{

                echo $this->_message_error("guardar");
            }								
        }

        public function get_form($id=NULL){
            if($id == NULL){
                $this->descripcion = NULL;
                $this->pais = NULL;

                $flag = NULL;
                $op = "new";
            }else{
                $sql = "SELECT * FROM marca WHERE id=$id;";
                $res = $this->con->query($sql);
                $row = $res->fetch_assoc();
                    
                $num = $res->num_rows;
                if($num==0){
                    $mensaje = "tratar de actualizar la marca con id= ".$id;
                    echo $this->_message_error($mensaje);
                }else{   
                    
                    // -- //
                    echo "<br>TUPLA <br>";
                    echo "<pre>";
                        print_r($row);
                    echo "</pre>";
                    
                    $this->descripcion = $row['descripcion'];
                    $this->pais = $row['pais'];;
                    $flag = "disabled";
                    $op = "update";
                }
            }
                
            $html = '

                <form name="marca" method="POST" action="marca.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="' . $id  . '">
                <input type="hidden" name="op" value="' . $op  . '">
                    <table class="table table-dark">
                        <tr>
                            <th colspan="2">DATOS MARCA</th>
                        </tr>
                        <tr>
                            <td>Descripcion:</td>
                            <td><input type="text" size="6" name="descripcion" value="' . $this->descripcion . '" required></td>
                        </tr>
                        <tr>
                            <td>Pais:</td>
                            <td><input type="text" size="6" name="pais" value="' . $this->pais . '" required></td>
                        </tr>
                        <tr>
                            <th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
                        </tr>
                    </table>
                    ';
            return $html;
        }

        public function get_list(){
            $d_new = "new/0";
            $d_new_final = base64_encode($d_new);
            $html = '

                <table class="table table-dark">
                    <tr>
                        <th class="bg-warning" colspan="8"><center>LISTA DE MARCAS</center></th>
                    </tr>
                    <tr>
                        <th colspan="8"><a href="marca.php?d=' . $d_new_final . '"><center>Nuevo</center></a></th>
                    </tr>
                    <tr>
                        <th>Descripcion</th>
                        <th>Pais</th>
                        <th colspan="3"><center>Acciones</center></th>
                    </tr>';
            $sql = "SELECT id, descripcion, pais
                    FROM marca;";	
            $res = $this->con->query($sql);
            // Sin codificar <td><a href="marca.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
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

                            <td><a href="marca.php?d=' . $d_del_final . '">Borrar</a></td>
                            <td><a href="marca.php?d=' . $d_act_final . '">Actualizar</a></td>
                            <td><a href="marca.php?d=' . $d_det_final . '">Detalle</a></td>
                        </tr>';
            }
            $html .= ' 
                    <tr>
                        <th colspan="8"><a href="index.html">Regresar</a></th>
                    </tr> 
                </table>
                ';
            return $html;
        }


        public function get_detail_marca($id){
            $sql = "SELECT id, descripcion, pais  
                    FROM marca WHERE id=$id";	
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();
            
            $num = $res->num_rows;
    
            //Si es que no existiese ningun registro debe desplegar un mensaje 
            //$mensaje = "tratar de eliminar el vehiculo con id= ".$id;
            //echo $this->_message_error($mensaje);
            //y no debe desplegarse la tablas
            
            if($num==0){
                $mensaje = "tratar de editar la marca con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{ 
                    $html = '
                
                        <table class="table table-dark">
                            <tr>
                                <th  class="bg-warning" colspan="2">DATOS DE LA MARCA</th>
                            </tr>
                            <tr>
                                <td>Descripcion: </td>
                                <td>'. $row['descripcion'] .'</td>
                            </tr>
                            <tr>
                                <td>Pais: </td>
                                <td>'. $row['pais'] .'</td>
                            </tr>
                            <tr>
                                <th colspan="2"><a href="marca.php">Regresar</a></th>
                            </tr>																						
                        </table>
               
                        ';
                    return $html;
            }
        }

        public function delete_marca($id){
            $sql = "DELETE FROM marca WHERE id={$id}";
                if($this->con->query($sql)){
                echo $this->_message_ok("eliminar");
            }else{
                echo $this->_message_error("eliminar");
                    var_dump($this->con->error);
            }	
        }

        private function _message_error($tipo){
            var_dump($this->con->error);
            $html = '
            <table border="0" align="center">
                <tr>
                    <th>Error al ' . $tipo . '. Favor contactar a .................... </th>
                </tr>
                <tr>
                    <th><a href="marca.php">Regresar</a></th>
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
                    <th><a href="marca.php">Regresar</a></th>
                </tr>
            </table>';
            return $html;
        }
    }
?>