<?php

    class Administracion extends Controller{
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
            $verificar = $this->model->verificarPermiso($id_usuario,'configuracion');

            if(!empty($verificar) || $id_usuario ==1){
                $data = $this->model->getEmpresa();
                $this->views->getView($this, "index",$data);
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

            
        }

        public function home()
        {
            $data['usuario'] = $this->model->getDatos('usuario');
            $data['cliente'] = $this->model->getDatos('cliente');
            $data['inventario'] = $this->model->getDatos('inventario');
            $data['venta'] = $this->model->getVentas();

            $this->views->getView($this, "home",$data);
        }

        public function modificar(){
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $mensaje = $_POST['mensaje'];
            $rfc = $_POST['rfc'];
            $idEmpresa = $_POST['idEmpresa'];
            $data = $this->model->modificar($nombre,$telefono,$direccion,$mensaje,$rfc,$idEmpresa);
        
            if($data == "ok"){
                $msg = "ok";
            } else {
                $msg = "error";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();

        }

        public function reporteStock(){
            $data = $this->model->getStockMinimo();
            echo json_encode($data);
            die();
        }

        public function productosMasVendidos(){
            $data = $this->model->getProductosMasVendidos();
            echo json_encode($data);
            die();
        }
    }


?>