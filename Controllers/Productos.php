<?php

    class Productos extends Controller{

        public function __construct(){
            session_start();
            parent::__construct();
        }

        public function index()
        {
            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'productos');

            if(!empty($verificar)){
               
                if(empty($_SESSION['activo'])){
                    header("location: ".base_url);
                }
                $data['categoria'] = $this->model->getCategorias();
                $this->views->getView($this, "index",$data);
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }
            
            
        }

        public function listar(){

           $data =  $this->model->getProductos();

           for ($i=0; $i < count($data); $i++) { 

                $data[$i]['foto'] = '<img class="img-thumbnail" src="'.base_url."Assets/inventario/".$data[$i]['foto'].'" width="100">';
              
                if($data[$i]['estado']==1){

                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-primary mb-3" onclick="btnEditarProducto('.$data[$i]['idinventario'].');" type="button"><li class="fas fa-edit"></li></button>
                        <button class="btn btn-danger mb-3" onclick="btnEliminarProducto('.$data[$i]['idinventario'].')" type="button"><li class="fas fa-trash-alt"></li></button>
                    </div>';

                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-success mb-3" onclick="btnActivarProducto('.$data[$i]['idinventario'].')" type="button">Activar</button>
                    </div>';
                }
                
           }

           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
            
        }

        public function registrar(){

            // print_r($_POST);
            // print_r($_FILES);
            // exit;

            $nombre = $_POST['nombre'];
            $precioEntrada = $_POST['precioEntrada'];
            $precioSalida = $_POST['precioSalida'];
            $descripcion = $_POST['descripcion'];
            $fabricante = $_POST['fabricante'];
            $categoria = $_POST['categoria'];
            $idinventario = $_POST['idinventario'];

            $imagen = $_FILES['foto'];
            $name = $imagen['name'];
            $tmpname = $imagen['tmp_name'];

            $balance = 0;
           
            $fecha = date("YmdHis");

            if(empty($nombre) || empty($precioEntrada) ||
                empty($precioSalida) || empty($descripcion) ||
                empty($fabricante) || empty($categoria)){

                    $msg = "Todos los campos son obligatorios";

                }else{

                    if(!empty($name)){
                        $imgNombre = $fecha.".jpg";
                        $destino = "Assets/inventario/".$imgNombre;
                    } else if(!empty($_POST['fotoActual']) && empty($name)){
                        $imgNombre = $_POST['fotoActual'];
                        $balance = 1;
                    } else{
                        $imgNombre = "default.png";
                    }

                    if($idinventario == ""){

                        $data = $this->model->registrarProducto($nombre,$precioEntrada,$precioSalida,$descripcion,$fabricante,$categoria,$imgNombre);

                        if($data == "ok"){

                            if(!empty($name)){
                                move_uploaded_file($tmpname,$destino); 
                            }
                            $msg = "si"; 
                                                      
                        } else if($data == "existe"){
                            $msg = "El Producto ya existe";
                        } else{
                            $msg = "Error al registrar el Producto";
                        }
                         
                    } else {

                        $imgDelete = $this->model->editarProducto($idinventario);
                        
                        if($balance == 0){
                            if($imgDelete['foto'] != 'default.png'){
                                if(file_exists("Assets/inventario/". $imgDelete['foto'])){
                                    unlink("Assets/inventario/". $imgDelete['foto']);
                                }
                            }
                        }

                        $data = $this->model->modificarProducto($nombre,$precioEntrada,$precioSalida,$descripcion,$fabricante,$categoria,$imgNombre,$idinventario);
                    
                        if($data == "modificado"){

                            if(!empty($name)){
                                move_uploaded_file($tmpname,$destino); 
                            }
                            $msg = "modificado"; 
                            
                        } else{
                            $msg = "Error al registrar el Producto";
                        }
                    }
                }

                header("Content-type: application/json; charset=utf-8");
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                
                die();
        }

        public function editar($idinventario){
            $data = $this->model->editarProducto($idinventario);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        public function eliminar($idinventario){
           $data = $this->model->accionProducto(0,$idinventario);
           
            if ($data == 1){
                $msg = "ok";
            } else {
                $msg = "Error al eliminar Producto";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function activar($idinventario){
            $data = $this->model->accionProducto(1,$idinventario);
             if ($data == 1){
                 $msg = "ok";
             } else {
                 $msg = "Error al activar el Producto";
             }
 
             echo json_encode($msg,JSON_UNESCAPED_UNICODE);
             die();
         }

    }


?>



