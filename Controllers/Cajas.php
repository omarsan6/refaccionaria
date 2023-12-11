<?php

    class Cajas extends Controller{

        public function __construct(){
            session_start();
            parent::__construct();

            if(empty($_SESSION['activo'])){
                header("location: ".base_url);
            }
            
        }

        public function index()
        {
            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'cajas');

            if(!empty($verificar)){
               
                $this->views->getView($this, "index");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

        
        }

        public function gananciaDia()
        {
            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'gananciaDia');

            if(!empty($verificar)){
               
                $this->views->getView($this, "gananciaDia");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

        
        }

        public function gananciaMes()
        {
            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'gananciaMes');

            if(!empty($verificar)){
               
                $this->views->getView($this, "gananciaMes");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }
        }

        public function arqueo()
        {
            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'arqueo_caja');

            if(!empty($verificar)){
               
                $this->views->getView($this, "arqueo");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }
            
        }

        public function validar(){

            if(empty($_POST['usuario']) || empty($_POST['password'])){

                $msg = "Los campos están vacios";

            } else{

                $usuario = $_POST['usuario'];

                $password = hash("sha256",$_POST['password']);

                $data = $this->model->getUsuario($usuario,$password);

                if($data){

                    $_SESSION['correo'] = $data['correo'];

                    $_SESSION['usuario'] = $data['usuario'];

                    $_SESSION['nombre'] = $data['nombre'];

                    $_SESSION['activo'] = true;

                    $msg = "ok";

                } else {

                    $msg = "Usuario o contraseña incorrecta";

                }
            }

            header("Content-type: application/json; charset=utf-8");
            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            
            die();
        }

        public function listar(){

           $data =  $this->model->getCajas('caja');

           for ($i=0; $i < count($data); $i++) { 
                if($data[$i]['estado']==1){
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-primary mb-3" onclick="btnEditarCaja('.$data[$i]['id'].');" type="button"><li class="fas fa-edit"></li></button>
                        <button class="btn btn-danger mb-3" onclick="btnEliminarCaja('.$data[$i]['id'].');" type="button"><li class="fas fa-trash-alt"></li></button>
                    </div>';
                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                    $data[$i]['acciones'] = '<div>
                <button class="btn btn-success mb-3" onclick="btnActivarCaja('.$data[$i]['id'].');" type="button">Activar</button>
                </div>';
                }
 

                
           }

           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
            
        }

        public function registrar(){
            
            $caja = $_POST['caja'];
            $descripcion = $_POST['descripcion'];
            
            $id = $_POST['id'];

            if(empty($caja) || empty($descripcion)){

                    $msg = "Todos los campos son obligatorios";

                }else{

                    if($id == ""){

                        $data = $this->model->registrarCaja($caja,$descripcion);

                        if($data == "ok"){
                            $msg = "si";
                                
                        } else if($data == "existe"){
                            $msg = "La caja ya existe";
                        } else{
                            $msg = "Error al registrar el cliente";
                        }
                        

                        
                    } else {

                        $data = $this->model->modificarCaja($id,$caja,$descripcion);

                        if($data == "modificado"){

                            $msg = "modificado";
                            
                        } else{
                            $msg = "Error al modificar el cliente";
                        }

                    }
                }

                header("Content-type: application/json; charset=utf-8");
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                
                die();
        }

        public function editar($id){
            $data = $this->model->editarCaja($id);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function eliminar($id){
           $data = $this->model->accionCaja(0,$id);
            if ($data == 1){
                $msg = "ok";
            } else {
                $msg = "Error al eliminar caja";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function activar($id){
            $data = $this->model->accionCaja(1,$id);
             if ($data == 1){
                 $msg = "ok";
             } else {
                 $msg = "Error al activar cliente";
             }
 
             echo json_encode($msg,JSON_UNESCAPED_UNICODE);
             die();
        }

        public function abrirArqueo(){
            $monto_inicial = $_POST['monto_inicial'];
            $fecha_apertura = date('Y-m-d');
            $id_usuario = $_SESSION['id_usuario'];
            $id_cierre_caja = $_POST['id_cierre_caja'];

            if(empty($monto_inicial)){

                $msg = array('msg'=>'Todos los campos son obligatorios','icono'=>'warning');

            }else{

                if($id_cierre_caja == ''){
                    $data = $this->model->registrarArqueo($id_usuario,$monto_inicial,$fecha_apertura);

                    if($data == 'ok'){
                        $msg = array('msg'=>'Caja abierta con éxito','icono'=>'success');
                    } else if ($data == 'existe'){
                        $msg = array('msg'=>'La caja ya está abierta','icono'=>'warning');
                    } else {
                        $msg = array('msg'=>'Error al abrir la caja','icono'=>'error');
                    }    

                } else {

                    $monto_final = $this->model->getVentas($id_usuario);

                    $total_ventas = $this->model->getTotalVentas($id_usuario);

                    $inical = $this->model->getMontoInicial($id_usuario);

                    $general = $monto_final['total'] + $inical['monto_inicial'];


                    $data = $this->model->actualizarArqueo($monto_final['total'],$fecha_apertura,$total_ventas['total'],$general,$inical['id_cierre_caja']);

                    if($data == 'ok'){
                        $this->model->actualizarApertura($id_usuario);
                        $msg = array('msg'=>'Caja cerrada con éxito','icono'=>'success');
                    }  else {
                        $msg = array('msg'=>'Error al cerrar la caja','icono'=>'error');
                    }
                    
                }
            }

            header("Content-type: application/json; charset=utf-8");
            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                
            die();
        }

        public function listarArqueo(){

            $data =  $this->model->getCajas('cierre_caja');
 
            for ($i=0; $i < count($data); $i++) { 
                 if($data[$i]['estado']==1){
                     $data[$i]['estado'] = '<span class="badge badge-success">Abierta</span>';
                 } else{
                     $data[$i]['estado'] = '<span class="badge badge-danger">Cerrada</span>';
                 }      
            }
 
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
             
        }

        public function getVentas(){
            $id_usuario = $_SESSION['id_usuario'];
            $data['monto_total'] = $this->model->getVentas($id_usuario);
            $data['total_ventas'] = $this->model->getTotalVentas($id_usuario);
            $data['monto_inicial'] = $this->model->getMontoInicial($id_usuario);
            $data['monto_general'] = $data['monto_total']['total'] + $data['monto_inicial']['monto_inicial'];
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function buscarProductoDia(){
            $fecha = $_POST['date'];

            if($fecha == ''){
                $msg = array("msg"=>"Ingrese una fecha","icono"=>"warning");
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                die();
            } else {
                $data = $this->model->buscarProductoDia($fecha);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                die();
            }
        }

        public function buscarProductoMes(){

            $fecha = $_POST['date'];
            $fecha2 = $_POST['date2'];

            if($fecha == '' || $fecha2 == ''){
                $msg = array("msg"=>"Ingrese una fecha","icono"=>"warning");
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                die();
            } else {
                $data = $this->model->buscarProductoMes($fecha,$fecha2);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                die();
            }
        }

    }


?>



