<?php

    class Proveedores extends Controller{

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
            $verificar = $this->model->verificarPermiso($id_usuario,'proveedores');

            if(!empty($verificar)){
               
                $this->views->getView($this, "index");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }
        }

        public function listar(){

           $data =  $this->model->getProveedores();

           for ($i=0; $i < count($data); $i++) { 
                if($data[$i]['estado']==1){
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-primary mb-3" onclick="btnEditarProveedor('.$data[$i]['id_proveedor'].');" type="button"><li class="fas fa-edit"></li></button>
                        <button class="btn btn-danger mb-3" onclick="btnEliminarProveedor('.$data[$i]['id_proveedor'].');" type="button"><li class="fas fa-trash-alt"></li></button>
                    </div>';
                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                    $data[$i]['acciones'] = '<div>
                <button class="btn btn-success mb-3" onclick="btnActivarProveedor('.$data[$i]['id_proveedor'].');" type="button">Activar</button>
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
            
            $id_proveedor = $_POST['id_proveedor'];

            if(empty($rfc) || empty($nombre) ||
                empty($correo) || empty($telefono) || 
                empty($direccion)){

                    $msg = "Todos los campos son obligatorios";

                }else{

                    if($id_proveedor == ""){

                        $data = $this->model->registrarProveedor($rfc,$nombre,$correo,$telefono,$direccion);

                        if($data == "ok"){
                            $msg = "si";
                                
                        } else if($data == "existe"){
                            $msg = "El cliente ya existe";
                        } else{
                            $msg = "Error al registrar el cliente";
                        }
                        

                        
                    } else {

                        $data = $this->model->modificarProveedor($id_proveedor,$rfc,$nombre,$correo,$telefono,$direccion);

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

        public function editar($id_proveedor){
            $data = $this->model->editarProveedor($id_proveedor);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        public function eliminar($id_proveedor){
           $data = $this->model->accionProveedor(0,$id_proveedor);
            if ($data == 1){
                $msg = "ok";
            } else {
                $msg = "Error al eliminar proveedor";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function activar($id_proveedor){
            $data = $this->model->accionProveedor(1,$id_proveedor);
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



