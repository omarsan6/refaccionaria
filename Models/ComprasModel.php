<?php

    class ComprasModel extends Query{

        private $idinventario;

        public function __construct(){
            parent::__construct();
        }

        public function getClientes(){
            $sql = "SELECT * FROM cliente where estado = 1";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getProductos(){
            $sql = "SELECT * FROM inventario where estado = 1";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getProductoInventario($idinventario){

            $this->idinventario = $idinventario;

            $sql = "SELECT * FROM inventario where idinventario = '$idinventario'";
            $data = $this->select($sql);
            return $data;
        }

        public function registrarDetalle($table,$id_inventario,$id_usuario,$precio,$cantidad,$subtotal){
            
            $sql = "INSERT INTO $table(id_inventario,id_usuario,precio,cantidad,subtotal)
                VALUES (?,?,?,?,?)";
            
            $datos = array($id_inventario,$id_usuario,$precio,$cantidad,$subtotal);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function getDetalle($table,$id_usuario){
            $sql = "SELECT d.*,i.idinventario,i.descripcion FROM $table d INNER JOIN inventario i ON d.id_inventario = i.idinventario where d.id_usuario = $id_usuario";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function calcularCompra($table,$id_usuario){
            $sql = "SELECT subtotal, SUM(subtotal) as total from $table where id_usuario = $id_usuario";
            $data = $this->select($sql);
            return $data;
        }

        public function deleteDetalle($tabla,$id_detalle){
            $sql = "DELETE FROM $tabla where id_detalle = ?";
            $datos = array($id_detalle);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function consultarDetalle($table,$id_inventario,$id_usuario){
            $sql = "SELECT * FROM $table where id_inventario = $id_inventario AND id_usuario = $id_usuario";
            $data = $this->select($sql);
            return $data;
        }

        public function actualizarDetalle($table,$precio,$cantidad,$subtotal,$id_inventario,$id_usuario){
            
            $sql = "UPDATE $table SET precio = ?, cantidad = ?, subtotal = ? WHERE id_inventario = ?  and id_usuario = ?";
            
            $datos = array($precio,$cantidad,$subtotal,$id_inventario,$id_usuario);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "modificado";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function registrarCompra($total){
            $sql = "INSERT INTO compra(total)
                VALUES (?)";
            
            $datos = array($total);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function registrarVenta($cliente_id_cliente,$total,$id_usuario){
            $sql = "INSERT INTO venta(totalVenta,cliente_id_cliente,id_usuario)
                VALUES (?,?,?)";
            
            $datos = array($total,$cliente_id_cliente,$id_usuario);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function getId($id,$tabla){
            $sql = "SELECT MAX($id) as id_compra from $tabla";
            $data = $this->select($sql);
            return $data;
        }

        public function registrarDetalleCompra($id_compra,$id_producto,$cantidad,$precio,$subtotal){
            $sql = "INSERT INTO detalle_compra(id_compra,id_producto, cantidad,precio,subtotal)
                VALUES(?,?,?,?,?)";
            
            $datos = array($id_compra,$id_producto,$cantidad,$precio,$subtotal);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
            
        }

        public function registrarDetalleVenta($noFolio,$id_producto,$cantidad,$descuento,$precio,$subtotal,$fecha){
            $sql = "INSERT INTO detalle_ventas(noFolio,id_producto, cantidad,descuento,precio,subtotal,fecha)
                VALUES(?,?,?,?,?,?,?)";
            
            $datos = array($noFolio,$id_producto,$cantidad,$descuento,$precio,$subtotal,$fecha);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
            
        }

        public function vaciarDetalle($tabla,$id_usuario){
            $sql = "DELETE from $tabla where id_usuario = ?";
            
            $datos = array($id_usuario);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function getProCompra($id_compra){
            $sql = "SELECT c.*,d.*,i.idinventario,i.nombre, i.descripcion FROM compra c 
                INNER JOIN detalle_compra d ON c.id_compra = d.id_compra
                INNER JOIN inventario i ON i.idinventario = d.id_producto
                WHERE c.id_compra = $id_compra";
            
            $data = $this->selectAll($sql);
            
            return $data;
        }

        public function getProVenta($id_venta){
            $sql = "SELECT c.*,d.*,i.idinventario,i.nombre, i.descripcion FROM venta c 
                INNER JOIN detalle_ventas d ON c.noFolio = d.noFolio
                INNER JOIN inventario i ON i.idinventario = d.id_producto
                WHERE c.noFolio = $id_venta";
            
            $data = $this->selectAll($sql);
            
            return $data;
        }

        public function getHistorialCompra(){
            $sql = "SELECT * FROM compra";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getEmpresa(){
            $sql = "SELECT * from empresa";
            $data = $this->select($sql);
            return $data;
        }

        public function actualizarStock($id_producto,$stock){
            $sql = "UPDATE inventario SET existencia = ? where idinventario = ?";
            $datos = array($stock,$id_producto);
            $data = $this->save($sql,$datos); 
            
            return $data; 
        }

        public function clientesVentas($id_venta){

            $sql = "SELECT v.noFolio, v.cliente_id_cliente,c.* FROM venta v
            INNER JOIN cliente c ON c.id_cliente = v.cliente_id_cliente 
            WHERE v.noFolio = $id_venta";

            $data = $this->select($sql);
            return $data;
        }

        public function verificarDescuento($id){
            $sql = "SELECT * FROM detalle_temp WHERE id_detalle = $id";

            $data = $this->select($sql);
            return $data;
        }

        public function actualizarDescuento($descuento,$subtotal,$id){
            $sql = "UPDATE detalle_temp set descuento = ?, subtotal = ? where id_detalle = ?";
            
            $datos = array($descuento,$subtotal,$id);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function getDescuento($id_venta){
            $sql = "SELECT descuento, SUM(descuento) as total from detalle_ventas where noFolio = $id_venta";
            $data = $this->select($sql);
            return $data;
        }

        public function getHistorialVenta(){
            $sql = "SELECT * FROM venta";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function getAnularCompra($id_compra){
            $sql = "SELECT c.*,d.* FROM compra c 
                INNER JOIN detalle_compra d ON c.id_compra = d.id_compra
                WHERE c.id_compra = $id_compra";
            
            $data = $this->selectAll($sql);
            
            return $data;
        }

        public function getAnular($id_compra){
            $sql = "UPDATE compra set estado = ? where id_compra = ?";
            
            $datos = array(0,$id_compra);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function getAnularVenta($noFolio){
            $sql = "SELECT v.*,d.* FROM venta v 
                INNER JOIN detalle_ventas d ON v.noFolio = d.noFolio
                WHERE v.noFolio = $noFolio";
            
            $data = $this->selectAll($sql);
            
            return $data;
        }

        public function getAnularV($noFolio){
            $sql = "UPDATE venta set estado = ? where noFolio = ?";
            
            $datos = array(0,$noFolio);
            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }

            return $res;
        }

        public function verificarCaja($id_usuario){
            $sql = "SELECT * from cierre_caja where id_usuario = $id_usuario
                and estado = 1";
            
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