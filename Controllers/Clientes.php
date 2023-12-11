<?php

    class Clientes extends Controller{

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
            $verificar = $this->model->verificarPermiso($id_usuario,'clientes');

            if(!empty($verificar)){
                $this->views->getView($this, "index");
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

           $data =  $this->model->getClientes();

           for ($i=0; $i < count($data); $i++) { 
                if($data[$i]['estado']==1){
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-primary mb-3" onclick="btnEditarCliente('.$data[$i]['id_cliente'].');" type="button"><li class="fas fa-edit"></li></button>
                        <button class="btn btn-danger mb-3" onclick="btnEliminarCliente('.$data[$i]['id_cliente'].');" type="button"><li class="fas fa-trash-alt"></li></button>
                    </div>';
                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                    $data[$i]['acciones'] = '<div>
                <button class="btn btn-success mb-3" onclick="btnActivarCliente('.$data[$i]['id_cliente'].');" type="button">Activar</button>
                </div>';
                }   
           }

           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
            
        }

        public function registrar(){
            
            $rfc = $_POST['rfc'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            
            $id_cliente = $_POST['id_cliente'];

            if(empty($rfc) || empty($nombre) ||
                empty($correo) || empty($telefono) || 
                empty($direccion)){

                    $msg = "Todos los campos son obligatorios";

                }else{

                    if($id_cliente == ""){

                        $data = $this->model->registrarCliente($rfc,$nombre,$correo,$telefono,$direccion);

                        if($data == "ok"){
                            $msg = "si";
                                
                        } else if($data == "existe"){
                            $msg = "El cliente ya existe";
                        } else{
                            $msg = "Error al registrar el cliente";
                        }
                        

                        
                    } else {

                        $data = $this->model->modificarCliente($id_cliente,$rfc,$nombre,$correo,$telefono,$direccion);

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

        public function editar($id_cliente){
            $data = $this->model->editarCliente($id_cliente);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        public function eliminar($id_cliente){
           $data = $this->model->accionCliente(0,$id_cliente);
            if ($data == 1){
                $msg = "ok";
            } else {
                $msg = "Error al eliminar cliente";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function activar($id_cliente){
            $data = $this->model->accionCliente(1,$id_cliente);
             if ($data == 1){
                 $msg = "ok";
             } else {
                 $msg = "Error al activar cliente";
             }
 
             echo json_encode($msg,JSON_UNESCAPED_UNICODE);
             die();
         }

         public function salir(){
            session_destroy();
            header("location: ".base_url);
         }
    }


?>



