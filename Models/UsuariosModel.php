<?php

    class UsuariosModel extends Query{

        private $rfc;

        private $nombre;

        private $usuario;

        private $password;

        private $telefono;

        private $correo;

        private $caja;

        private $estado;

        private $id_usuario;

        public function __construct(){
            parent::__construct();
        }

        public function getUsuario($usuario, $password){
            $sql = "SELECT * FROM usuario WHERE usuario = '$usuario' and contrasenia = '$password'";
            $data = $this->select($sql);
            return $data;
        }

        public function getCajas(){
            $sql = "SELECT * FROM caja where estado = 1";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getPermisos(){
            $sql = "SELECT * FROM permisos";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getUsuarios(){
            $sql = "SELECT u.id_usuario,u.rfc,u.nombre,u.telefono,u.correo,u.estado,c.id,c.caja FROM usuario u INNER JOIN caja c where u.caja_id = c.id";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function registrarUsuario($rfc,$nombre,$usuario,$password,$telefono,$correo,$caja){

            $this->rfc = $rfc;
            $this->nombre = $nombre;
            $this->usuario = $usuario;
            $this->password = $password;
            $this->telefono = $telefono;
            $this->correo = $correo;
            $this->caja = $caja;

            $verificar = "SELECT * FROM usuario WHERE usuario = '$this->usuario'";
            $existe = $this->select($verificar);

            if(empty($existe))
            {
                $sql = "INSERT INTO usuario(rfc,nombre,usuario,contrasenia,telefono,correo,caja_id) 
                    VALUES (?,?,?,?,?,?,?)";
            
                $datos = array($this->rfc,$this->nombre, $this->usuario,$this->password,
                $this->telefono,  $this->correo, $this->caja);

                $data = $this->save($sql,$datos);

                if($data == 1){
                    $res = "ok";
                } else {
                    $res = "error";
                }
            } else {
                $res = "existe";
            }
            return $res;
        }

        public function modificarUsuario($rfc,$nombre,$usuario,$telefono,$correo,$caja,$id_usuario){

            $this->rfc = $rfc;
            $this->nombre = $nombre;
            $this->usuario = $usuario;
            $this->id_usuario = $id_usuario;
            $this->telefono = $telefono;
            $this->correo = $correo;
            $this->caja = $caja;
            $this->id_usuario = $id_usuario;

            $sql = "UPDATE usuario SET rfc = ?,nombre = ?,usuario = ?,telefono = ?,
            correo = ?, caja_id = ? where id_usuario = ?";
            
            $datos = array($this->rfc,$this->nombre, $this->usuario,$this->telefono,
            $this->correo, $this->caja, $this->id_usuario);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "modificado";
            } else {
                $res = "error";
            }  

            return $res;
        }

        public function editarUsuario($id_usuario){
            $sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
            
            $data = $this->select($sql);

            return $data;
        }

        public function accionUsuario($estado,$id_usuario){

            $this->estado = $estado;

            $this->id_usuario = $id_usuario;

            $sql = "UPDATE usuario SET estado = ? WHERE id_usuario = ?";
            $datos = array($this->estado,$this->id_usuario);
            $data = $this->save($sql,$datos);
            return $data;
        }

        public function registrarPermisos($id_usuario,$id_permiso){
            $sql = "INSERT INTO detalle_permisos(id_usuario,id_permiso)
                VALUES (?,?)";

            $datos = array($id_usuario,$id_permiso);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function eliminarPermisos($id_usuario){
            $sql = "DELETE FROM detalle_permisos where id_usuario = ?";

            $datos = array($id_usuario);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        } 

        public function getDetallePermisos($id_usuario){
            $sql = "SELECT * FROM detalle_permisos where id_usuario = $id_usuario";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function verificarPermiso($id_usuario,$permisos){
            $sql = "SELECT p.id_permiso, p.permiso,d.id_detalle_permisos, d.id_usuario,d.id_permiso
            from permisos p inner join detalle_permisos d on p.id_permiso = d.id_permiso where d.id_usuario = $id_usuario
            and p.permiso = '$permisos'";

            $data = $this->selectAll($sql);

            return $data;
        }
    }


?>