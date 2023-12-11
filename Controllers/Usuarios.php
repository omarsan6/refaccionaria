<?php

    class Usuarios extends Controller{

        public function __construct(){
            session_start();
            parent::__construct();
        }

        public function index()
        {
            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'usuarios');

            if(!empty($verificar)){
                if(empty($_SESSION['activo'])){
                    header("location: ".base_url);
                }
                $data['cajas'] = $this->model->getCajas();
                $this->views->getView($this, "index",$data);
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

            
        }

        public function validar(){

            if(empty($_POST['usuario']) || empty($_POST['password'])){

                $msg = "Los campos están vacios";

            } else{

                $usuario = $_POST['usuario'];

                $password = $_POST['password'];

                $data = $this->model->getUsuario($usuario,$password);

                if($data){

                    $_SESSION['correo'] = $data['correo'];

                    $_SESSION['usuario'] = $data['usuario'];

                    $_SESSION['nombre'] = $data['nombre'];

                    $_SESSION['id_usuario'] = $data['id_usuario'];

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

           $data =  $this->model->getUsuarios();

           for ($i=0; $i < count($data); $i++) { 


                if($data[$i]['estado']==1){

                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';

                    if($data[$i]['id_usuario'] == 1){
                        $data[$i]['acciones'] = '<div>
                            <span class="badge badge-success">Administrador</span>
                        </div>';
                    } else {
                        $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                
                        $data[$i]['acciones'] = '<div>
                            
                            <a class="btn btn-dark mb-3" href="'.base_url.'Usuarios/permisos/'.$data[$i]['id_usuario'].'"><li class="fas fa-key"></li></a>
                            <button class="btn btn-primary mb-3" onclick="btnEditarUsuario('.$data[$i]['id_usuario'].');" type="button"><li class="fas fa-edit"></li></button>
                            <button class="btn btn-danger mb-3" onclick="btnEliminarUsuario('.$data[$i]['id_usuario'].')" type="button"><li class="fas fa-trash-alt"></li></button>
                        </div>';
                    }

                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-success mb-3" onclick="btnActivarUsuario('.$data[$i]['id_usuario'].')" type="button">Activar</button>
                    </div>';
                }
                
           }

           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
            
        }

        public function registrar(){
            
            $rfc = $_POST['rfc'];
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            $confirmar = $_POST['confirmar'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $caja = $_POST['caja'];
            
            $id_usuario = $_POST['id_usuario'];

            if(empty($rfc) || empty($nombre) || empty($usuario) ||
                empty($telefono) || empty($correo) ||
                empty($caja)){

                    $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');

                }else{

                    if($id_usuario == ""){


                        if($password != $confirmar){

                            $msg = array('msg' => 'Las contraseñas no coinciden', 'icono' => 'warning');

                        } else {

                            $data = $this->model->registrarUsuario($rfc,$nombre,$usuario,$password,$telefono,$correo,$caja);

                            if($data == "ok"){
        
                                $msg = array('msg' => 'Usuario registrado con exito', 'icono' => 'success');

                            } else if($data == "existe"){

                                $msg = array('msg' => 'El usuario ya existe', 'icono' => 'warning');

                            } else{

                                $msg = array('msg' => 'Error al registrar usuario', 'icono' => 'error');
                            }
                        }

                        
                    } else {

                        $data = $this->model->modificarUsuario($rfc,$nombre,$usuario,$telefono,$correo,$caja,$id_usuario);

                        if($data == "modificado"){

                            $msg = array('msg' => 'Usuario modificado correctamente', 'icono' => 'success');

                        } else{
                            $msg = array('msg' => 'Error al registrar usuario', 'icono' => 'error');

                        }

                    }
                }

                header("Content-type: application/json; charset=utf-8");
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                
                die();
        }

        public function editar($id_usuario){
            $data = $this->model->editarUsuario($id_usuario);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        public function eliminar($id_usuario){
           $data = $this->model->accionUsuario(0,$id_usuario);
            if ($data == 1){

                $msg = array('msg' => 'Usuario desactivado', 'icono' => 'success');

            } else {
                
                $msg = array('msg' => 'Error al desactivar usuario', 'icono' => 'error');

            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function activar($id_usuario){
            $data = $this->model->accionUsuario(1,$id_usuario);

            if ($data == 1){

                $msg = array('msg' => 'Usuario activado', 'icono' => 'success');

            } else {
                
                $msg = array('msg' => 'Error al activar usuario', 'icono' => 'error');

            }
             echo json_encode($msg,JSON_UNESCAPED_UNICODE);
             die();
         }

         public function permisos($id_usuario)
        {
            if(empty($_SESSION['activo'])){
                header("location: ".base_url);
            }

            $data['datos'] = $this->model->getPermisos();

            $permisos = $this->model->getDetallePermisos($id_usuario);

            $data['asignados'] = array();

            foreach($permisos as $permiso){
                $data['asignados'][$permiso['id_permiso']] = true;
            }

            $data['id_usuario'] = $id_usuario;

            $this->views->getView($this, "permisos",$data);
        }

        public function registrarPermisos(){
            $id_usuario = $_POST['id_usuario'];

            $eliminar = $this->model->eliminarPermisos($id_usuario);

            $msg = '';

            if($eliminar == "ok"){
                foreach($_POST['permisos'] as $id_permiso){
                    $msg = $this->model->registrarPermisos($id_usuario,$id_permiso);
                }

                if($msg == "ok"){
                    $msg = array('msg' => 'Permisos asignados', 'icono'=>'success');
                } else {
                    $msg = array('msg' => 'Error al eliminar al asignar los permisos', 'icono'=>'error');
                }
    
            } else{
                $msg = array('msg' => 'Error al eliminar los permisos anteriores', 'icono'=>'error');
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



