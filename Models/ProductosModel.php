<?php

    class ProductosModel extends Query{

        private $nombre;

        private $precioEntrada;

        private $precioSalida;

        private $descripcion;

        private $fabricante;

        private $categoria;

        private $estado;

        private $idinventario;

        private $name;

        public function __construct(){
            parent::__construct();
        }


        public function getCategorias(){
            $sql = "SELECT * FROM categoria where estado = 1";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getProductos(){
            $sql = "SELECT i.*,c.idcategoria,c.nombre as categoria FROM inventario i INNER JOIN categoria c ON c.idcategoria = i.categoria_idcategoria where i.estado = 1";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function registrarProducto($nombre,$precioEntrada,$precioSalida,$descripcion,$fabricante,$categoria,$name){

            $this->nombre = $nombre;
            $this->precioEntrada = $precioEntrada;
            $this->precioSalida = $precioSalida;
            $this->descripcion = $descripcion;
            $this->fabricante = $fabricante;
            $this->categoria = $categoria;
            $this->name = $name;

            $verificar = "SELECT * FROM inventario WHERE nombre = '$this->nombre'";
            $existe = $this->select($verificar);

            if(empty($existe))
            {
                $sql = "INSERT INTO inventario(nombre,precioEntrada,precioSalida,descripcion,fabricante,categoria_idcategoria,foto) 
                    VALUES (?,?,?,?,?,?,?)";
            
                $datos = array($this->nombre, $this->precioEntrada,$this->precioSalida,
                $this->descripcion,  $this->fabricante, $this->categoria,$this->name);

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

        public function modificarProducto($nombre,$precioEntrada,$precioSalida,$descripcion,$fabricante,$categoria,$name,$idinventario){

            $this->nombre = $nombre;
            $this->precioEntrada = $precioEntrada;
            $this->precioSalida = $precioSalida;
            $this->descripcion = $descripcion;
            $this->fabricante = $fabricante;
            $this->categoria = $categoria;
            $this->name = $name;

            $this->idinventario = $idinventario;

            $sql = "UPDATE inventario SET nombre = ?,precioEntrada = ?,precioSalida = ?,
            descripcion = ?, fabricante = ?, categoria_idcategoria = ?,foto = ? where idinventario = ?";
            
            $datos = array($this->nombre, $this->precioEntrada,$this->precioSalida,
            $this->descripcion,  $this->fabricante, $this->categoria,$this->name, $this->idinventario);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "modificado";
            } else {
                $res = "error";
            }  

            return $res;
        }

        public function editarProducto($idinventario){
            $sql = "SELECT * FROM inventario WHERE idinventario = $idinventario";
            
            $data = $this->select($sql);

            return $data;
        }

        public function accionProducto($estado,$idinventario){

            $this->estado = $estado;
            $this->idinventario = $idinventario;

            $sql = "UPDATE inventario SET estado = ? WHERE idinventario = ?";
            $datos = array($this->estado,$this->idinventario);
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