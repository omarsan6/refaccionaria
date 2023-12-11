<?php

    class Categorias extends Controller{

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
            $verificar = $this->model->verificarPermiso($id_usuario,'categorias');

            if(!empty($verificar)){
                $this->views->getView($this, "index");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }
        }

        public function listar(){

           $data =  $this->model->getCategorias();

           for ($i=0; $i < count($data); $i++) { 
                if($data[$i]['estado']==1){
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-primary mb-3" onclick="btnEditarCategoria('.$data[$i]['idcategoria'].');" type="button"><li class="fas fa-edit"></li></button>
                        <button class="btn btn-danger mb-3" onclick="btnEliminarCategoria('.$data[$i]['idcategoria'].');" type="button"><li class="fas fa-trash-alt"></li></button>
                    </div>';
                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                    $data[$i]['acciones'] = '<div>
                <button class="btn btn-success mb-3" onclick="btnActivarCategoria('.$data[$i]['idcategoria'].');" type="button">Activar</button>
                </div>';
                }
 

                
           }

           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
            
        }

        public function registrar(){
            
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            
            $idcategoria = $_POST['idcategoria'];

            if(empty($nombre) || empty($descripcion)){

                    $msg = "Todos los campos son obligatorios";

                }else{

                    if($idcategoria == ""){

                        $data = $this->model->registrarCategoria($nombre,$descripcion);

                        if($data == "ok"){
                            $msg = "si";
                                
                        } else if($data == "existe"){
                            $msg = "La categoria ya existe";
                        } else{
                            $msg = "Error al registrar la categoria";
                        }
                        

                        
                    } else {

                        $data = $this->model->modificarCategoria($idcategoria,$nombre,$descripcion);

                        if($data == "modificado"){

                            $msg = "modificado";
                            
                        } else{
                            $msg = "Error al modificar el categoria";
                        }

                    }
                }

                header("Content-type: application/json; charset=utf-8");
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                
                die();
        }

        public function editar($idcategoria){
            $data = $this->model->editarCategoria($idcategoria);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        public function eliminar($idcategoria){
           $data = $this->model->accionCategoria(0,$idcategoria);
            if ($data == 1){
                $msg = "ok";
            } else {
                $msg = "Error al eliminar categoria";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function activar($idcategoria){
            $data = $this->model->accionCategoria(1,$idcategoria);
             if ($data == 1){
                 $msg = "ok";
             } else {
                 $msg = "Error al activar cliente";
             }
 
             echo json_encode($msg,JSON_UNESCAPED_UNICODE);
             die();
         }

    }


?>



