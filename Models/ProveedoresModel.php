<?php

    class ProveedoresModel extends Query{

        private $rfc;

        private $nombre;

        private $telefono;

        private $correo;

        private $direccion;

        private $id_proveedor;

        public function __construct(){
            parent::__construct();
        }

        public function getProveedores(){
            $sql = "SELECT * from proveedor";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function registrarProveedor($rfc,$nombre,$correo,$telefono,$direccion){

            $this->rfc = $rfc;
            $this->nombre = $nombre;
            $this->correo = $correo;
            $this->telefono = $telefono;
            $this->direccion = $direccion;

            $verificar = "SELECT * FROM proveedor WHERE RFC = '$this->rfc'";
            $existe = $this->select($verificar);

            if(empty($existe))
            {
                $sql = "INSERT INTO proveedor(rfc,nombre,correo,telefono,direccion) 
                    VALUES (?,?,?,?,?)";
            
                $datos = array($this->rfc, $this->nombre,$this->correo,
                $this->telefono, $this->direccion);

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

        public function modificarProveedor($id_proveedor,$rfc,$nombre,$correo,$telefono,$direccion){

            $this->rfc = $rfc;
            $this->nombre = $nombre;
            $this->telefono = $telefono;
            $this->correo = $correo;
            $this->direccion = $direccion;
            $this->id_proveedor = $id_proveedor;

            $sql = "UPDATE proveedor SET RFC=?,nombre=?,correo=?,telefono=?,direccion=? WHERE id_proveedor = ?";
            
            $datos = array($this->rfc,$this->nombre, $this->correo,
            $this->telefono,$this->direccion, $this->id_proveedor);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "modificado";
            } else {
                $res = "error";
            }  

            return $res;
        }

        public function editarProveedor($id_proveedor){
            $sql = "SELECT * FROM proveedor WHERE id_proveedor = $id_proveedor";
            
            $data = $this->select($sql);

            return $data;
        }

        public function accionProveedor($estado,$id_proveedor){

            $this->estado = $estado;

            $this->id_proveedor = $id_proveedor;

            $sql = "UPDATE proveedor SET estado = ? WHERE id_proveedor = ?";
            $datos = array($this->estado,$this->id_proveedor);
            $data = $this->save($sql,$datos);
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