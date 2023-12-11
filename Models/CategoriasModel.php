<?php

    class CategoriasModel extends Query{

        private $nombre;

        private $descripcion;

        private $idcategoria;

        private $estado;

        public function __construct(){
            parent::__construct();
        }

        public function getCategorias(){
            $sql = "SELECT * from categoria";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function registrarCategoria($nombre,$descripcion){

            $this->nombre = $nombre;
            $this->descripcion = $descripcion;

            $verificar = "SELECT * FROM categoria WHERE nombre = '$this->nombre'";
            $existe = $this->select($verificar);

            if(empty($existe))
            {
                $sql = "INSERT INTO categoria(nombre,descripcion) 
                    VALUES (?,?)";
            
                $datos = array($this->nombre, $this->descripcion);

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

        public function modificarCategoria($idcategoria,$nombre,$descripcion){

            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
    
            $this->idcategoria = $idcategoria;

            $sql = "UPDATE categoria SET nombre=?,descripcion=? WHERE idcategoria = ?";
            
            $datos = array($this->nombre,$this->descripcion,$this->idcategoria);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "modificado";
            } else {
                $res = "error";
            }  

            return $res;
        }

        public function editarCategoria($idcategoria){
            $sql = "SELECT * FROM categoria WHERE idcategoria = $idcategoria";
            
            $data = $this->select($sql);

            return $data;
        }

        public function accionCategoria($estado,$idcategoria){

            $this->estado = $estado;

            $this->idcategoria = $idcategoria;

            $sql = "UPDATE categoria SET estado = ? WHERE idcategoria = ?";
            $datos = array($this->estado,$this->idcategoria);
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