<?php

    class CajasModel extends Query{

        private $caja;

        private $descripcion;

        private $id;

        private $estado;

        public function __construct(){
            parent::__construct();
        }

        public function getCajas($table){
            $sql = "SELECT * from $table";
            $data = $this->selectAll($sql);
            return $data;
        }

        public function registrarCaja($caja,$descripcion){

            $this->caja = $caja;
            $this->descripcion = $descripcion;

            $verificar = "SELECT * FROM caja WHERE caja = '$this->caja'";
            $existe = $this->select($verificar);

            if(empty($existe))
            {
                $sql = "INSERT INTO caja(caja,descripcion) 
                    VALUES (?,?)";
            
                $datos = array($this->caja, $this->descripcion);

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

        public function modificarCaja($id,$caja,$descripcion){

            $this->caja = $caja;
            $this->descripcion = $descripcion;
    
            $this->id = $id;

            $sql = "UPDATE caja SET caja=?,descripcion=? WHERE id = ?";
            
            $datos = array($this->caja,$this->descripcion,$this->id);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "modificado";
            } else {
                $res = "error";
            }  

            return $res;
        }

        public function editarCaja($id){
            $sql = "SELECT * FROM caja WHERE id = $id";
            
            $data = $this->select($sql);

            return $data;
        }

        public function accionCaja($estado,$id){

            $this->estado = $estado;

            $this->id = $id;

            $sql = "UPDATE caja SET estado = ? WHERE id = ?";
            $datos = array($this->estado,$this->id);
            $data = $this->save($sql,$datos);
            return $data;
        }

        public function registrarArqueo($id_usuario,$monto_inicial,$fecha_apertura){

            $verificar = "SELECT * FROM cierre_caja WHERE id_usuario = '$id_usuario' and 
            estado = 1";

            $existe = $this->select($verificar);

            if(empty($existe))
            {
                $sql = "INSERT INTO cierre_caja(id_usuario,monto_inicial,fecha_apertura) 
                    VALUES (?,?,?)";
            
                $datos = array($id_usuario,$monto_inicial,$fecha_apertura);

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

        public function getVentas($id_usuario){
            $sql = "SELECT totalVenta, SUM(totalVenta) as total FROM venta 
                WHERE id_usuario = $id_usuario
                AND estado = 1
                AND apertura = 1";
            
            $data = $this->select($sql);

            return $data;
        }

        public function getTotalVentas($id_usuario){
            $sql = "SELECT COUNT(totalVenta) as total FROM venta 
                WHERE id_usuario = $id_usuario
                AND estado = 1
                And apertura = 1";
            
            $data = $this->select($sql);

            return $data;
        }

        public function getMontoInicial($id_usuario){
            $sql = "SELECT id_cierre_caja,monto_inicial FROM cierre_caja 
                WHERE id_usuario = $id_usuario
                AND estado = 1";
            
            $data = $this->select($sql);

            return $data;
        }

        public function actualizarArqueo($monto_final,$fecha_apertura,$total_ventas,$general,$id_cierre_caja){

            $sql = "UPDATE cierre_caja SET monto_final = ?, fecha_cierre = ?, total_ventas = ?,monto_total = ?, estado = ? 
                WHERE id_cierre_caja = ?";
        
            $datos = array($monto_final,$fecha_apertura,$total_ventas,$general,0,$id_cierre_caja);

            $data = $this->save($sql,$datos);

            if($data == 1){
                $res = "ok";
            } else {
                $res = "error";
            }
         
            return $res;
        }

        public function actualizarApertura($id_usuario){
            $sql = "UPDATE venta SET apertura = ? 
                WHERE id_usuario = ?";
        
            $datos = array(0,$id_usuario);

            $this->save($sql,$datos);
        }

        public function verificarPermiso($id_usuario,$permisos){
            $sql = "SELECT p.id_permiso, p.permiso,d.id_detalle_permisos, d.id_usuario,d.id_permiso
            from permisos p inner join detalle_permisos d on p.id_permiso = d.id_permiso where d.id_usuario = $id_usuario
            and p.permiso = '$permisos'";

            $data = $this->selectAll($sql);

            return $data;
        }


        public function buscarProductoDia($fecha){
            $sql = "SELECT  i.nombre, i.precioEntrada, i.precioSalida, i.precioSalida - i.precioEntrada as ganancia, A.cantidad, (i.precioSalida - i.precioEntrada) * A.cantidad subtotal,A.descuento, (i.precioSalida - i.precioEntrada) * A.cantidad - A.descuento total
            FROM inventario i,
                (SELECT id_producto, sum(cantidad) cantidad, fecha, sum(descuento) descuento
                FROM detalle_ventas 
                inner join venta
                on detalle_ventas.noFolio = venta.noFolio
                WHERE fecha = '$fecha' and venta.estado = 1
                group by id_producto) as A
            where A.id_producto = i.idinventario";
        
            $data = $this->selectAll($sql);

            return $data;
        }

        public function buscarProductoMes($fecha,$fecha2){

            $sql = "SELECT  i.nombre, i.precioEntrada, i.precioSalida, i.precioSalida - i.precioEntrada as ganancia, A.cantidad, (i.precioSalida - i.precioEntrada) * A.cantidad subtotal,A.descuento, (i.precioSalida - i.precioEntrada) * A.cantidad - A.descuento total
                FROM inventario i,
                    (SELECT id_producto, sum(cantidad) cantidad, fecha, sum(descuento) descuento
                    FROM detalle_ventas 
                    inner join venta
                    on detalle_ventas.noFolio = venta.noFolio
                    WHERE fecha BETWEEN '$fecha' and '$fecha2' and venta.estado = 1
                    group by id_producto) as A
                where A.id_producto = i.idinventario";
        
            $data = $this->selectAll($sql);

            return $data;
        }

    }


?>