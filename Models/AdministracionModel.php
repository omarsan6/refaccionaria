<?php

    class AdministracionModel extends Query{

        private $idEmpresa;

        private $nombre;

        private $telefono;

        private $direccion;

        private $mensaje;

        private $rfc;

        public function __construct(){
            parent::__construct();
        }

        public function getEmpresa(){
            $sql = "SELECT * from empresa";
            $data = $this->select($sql);
            return $data;
        }

        public function modificar($nombre,$telefono,$direccion,$mensaje,$rfc,$idEmpresa){
            $this->nombre = $nombre;
            $this->telefono = $telefono;
            $this->direccion = $direccion;
            $this->mensaje = $mensaje;
            $this->rfc = $rfc;
            $this->idEmpresa = $idEmpresa;

            $sql = "UPDATE empresa SET nombre = ?, telefono = ?, direccion = ?, mensaje = ?, rfc = ? where idEmpresa = ?";
            
            $datos = array($this->nombre, $this->telefono,$this->direccion,$this->mensaje,$this->rfc,$this->idEmpresa);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }
            
            return $res;
        }

        public function getDatos($tabla){
            $sql = "SELECT COUNT(*) as total from $tabla";
            $data = $this->select($sql);
            return $data;
        }

        public function getVentas(){
            $sql = "SELECT COUNT(*) as total from venta where fechaVenta > CURDATE()";
            $data = $this->select($sql);
            return $data;
        }

        public function getStockMinimo(){
            $sql = "SELECT * FROM inventario where existencia < 15 ORDER BY existencia desc limit 10";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getProductosMasVendidos(){
            $sql = "SELECT d.id_producto,d.cantidad,i.idinventario,i.nombre,SUM(d.cantidad) as total from detalle_ventas d
            inner join inventario i ON i.idinventario = d.id_producto GROUP BY d.id_producto order by d.cantidad desc limit 10";

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