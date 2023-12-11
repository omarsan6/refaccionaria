<?php

    class Compras extends Controller{
        public function __construct(){
            session_start();
            parent::__construct();
        }

        public function index(){

            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'nueva_compra');

            if(!empty($verificar)){

                $data = $this->model->getProductos();

                $this->views->getView($this, "index",$data);

            } else{
                header("Location: ".base_url.'Errors/permisos');
            }
           
        }

        public function ventas(){

            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'nueva_venta');

            if(!empty($verificar)){
                $data = $this->model->getClientes();
                $data2 = $this->model->getProductos();

                $datos = array('cliente' => $data, 'producto' => $data2);


                $this->views->getView($this,"ventas",$datos);
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

            
        }

        function buscarCodigo($idinventario){
            
            $data = $this->model->getProductoInventario($idinventario);
            
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            die();
        }

        function ingresar(){

            $idinventario = $_POST['idinventario'];
            $datos = $this->model->getProductoInventario($idinventario);

            $id_inventario = $_POST['idinventario'];
            $id_usuario = $_SESSION['id_usuario'];
            $precio = $datos['precioEntrada'];
            $cantidad = $_POST['cantidad'];

            $combrobar = $this->model->consultarDetalle('detalle',$id_inventario,$id_usuario);

            if(empty($combrobar)){

                $subtotal = $precio * $cantidad;

                $data = $this->model->registrarDetalle('detalle',$id_inventario,$id_usuario,$precio,$cantidad,$subtotal);
        
                if($data == "ok"){
                    $msg = "ok";
                } else {
                    $msg = "Error al ingresar el producto";
                }
            } else{

                $total_cantidad = $combrobar['cantidad'] + $cantidad;
                
                $subtotal  = $total_cantidad * $precio;

                $data = $this->model->actualizarDetalle('detalle',$precio,$total_cantidad,$subtotal,$id_inventario,$id_usuario);
        
                if($data == "modificado"){
                    $msg = "modificado";
                } else {
                    $msg = "Error al modificar el producto";
                }
            }
            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        
        function ingresarVenta(){

            $idinventario = $_POST['idinventario'];
            $datos = $this->model->getProductoInventario($idinventario);

            $id_inventario = $_POST['idinventario'];
            $id_usuario = $_SESSION['id_usuario'];
            $precio = $datos['precioSalida'];
            $cantidad = $_POST['cantidad'];

            $combrobar = $this->model->consultarDetalle('detalle_temp',$id_inventario,$id_usuario);

            if(empty($combrobar)){

                if($datos['existencia'] >= $cantidad){
                    $subtotal = $precio * $cantidad;

                    $data = $this->model->registrarDetalle('detalle_temp',$id_inventario,$id_usuario,$precio,$cantidad,$subtotal);
            
                    if($data == "ok"){
                        $msg = array('msg'=>'Producto ingresado', 'icono'=>'success');
                    } else {
                        $msg = array('msg'=>'Error al ingresar producto', 'icono'=>'error');
                    }
                } else {
                    $msg = array('msg'=>'Stock no disponible', 'icono'=>'warning');
                }

                
            } else{

                $total_cantidad = $combrobar['cantidad'] + $cantidad;
                
                $subtotal  = $total_cantidad * $precio;

                if($datos['existencia'] < $total_cantidad){
                    $msg = array('msg'=>'Stock no disponible', 'icono'=>'warning');
                } else{
                    $data = $this->model->actualizarDetalle('detalle_temp',$precio,$total_cantidad,$subtotal,$id_inventario,$id_usuario);
        
                    if($data == "modificado"){
                        $msg = array('msg'=>'Producto modificado correctamente', 'icono'=>'success');
                    } else {
                        $msg = array('msg'=>'Error al modificar el producto', 'icono'=>'error');
                    }
                }

            }
            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }


        function listar($table){
            $id_usuario = $_SESSION['id_usuario'];
            $data['detalle'] = $this->model->getDetalle($table,$id_usuario);
            $data['subtotal'] = $this->model->calcularCompra($table,$id_usuario);
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function delete($id_detalle){
            $data = $this->model->deleteDetalle('detalle',$id_detalle);

            if($data == "ok"){
                $msg = "ok";
            }else{
                $msg = "error";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function deleteVenta($id_detalle){
            $data = $this->model->deleteDetalle('detalle_temp',$id_detalle);

            if($data == "ok"){
                $msg = "ok";
            }else{
                $msg = "error";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function registrarCompra($cliente_id_cliente){
            $id_usuario = $_SESSION['id_usuario'];

            $total = $this->model->calcularCompra('detalle',$id_usuario);

            $data = $this->model->registrarCompra($total['total']);

            if($data == "ok"){

                $detalle = $this->model->getDetalle('detalle',$id_usuario);

                $id_compra = $this->model->getId('id_compra','compra');

                foreach($detalle as $row){
                    $cantidad = $row['cantidad'];

                    $precio = $row['precio'];

                    $id_producto = $row['id_inventario'];

                    $subtotal = $cantidad * $precio;

                    $this->model->registrarDetalleCompra($id_compra['id_compra'],$id_producto,$cantidad,$precio,$subtotal);
                    
                    $stock_actual = $this->model->getProductoInventario($id_producto);

                    $stock = $stock_actual['existencia'] + $cantidad;

                    $this->model->actualizarStock($id_producto,$stock);
                }

                $vaciar = $this->model->vaciarDetalle('detalle',$id_usuario);

                if($vaciar == "ok"){
                    $msg = array('msg' => 'ok', 'id_compra' => $id_compra['id_compra']);
                }

            } else {
                $msg = "Error al realizar la compra";
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function registrarVenta($cliente_id_cliente){

            $id_usuario = $_SESSION['id_usuario'];

            $verificar = $this->model->verificarCaja($id_usuario);

            if(empty($verificar)){
                $msg = array('msg'=>'La caja está cerrada','icono'=>'warning');
            } else{

                $total = $this->model->calcularCompra('detalle_temp',$id_usuario);

                $data = $this->model->registrarVenta($cliente_id_cliente,$total['total'],$id_usuario);

                if($data == "ok"){

                    $detalle = $this->model->getDetalle('detalle_temp',$id_usuario);

                    $id_venta = $this->model->getId('noFolio','venta');

                    foreach($detalle as $row){
                        $cantidad = $row['cantidad'];

                        $descuento = $row['descuento'];

                        $precio = $row['precio'];

                        $id_producto = $row['id_inventario'];

                        $subtotal = ($cantidad * $precio) - $descuento;

                        date_default_timezone_set('America/Mexico_City');
                        $fecha = date('Y/m/d');

                        $this->model->registrarDetalleVenta($id_venta['id_compra'],$id_producto,$cantidad,$descuento,$precio,$subtotal,$fecha);
                        
                        $stock_actual = $this->model->getProductoInventario($id_producto);

                        $stock = $stock_actual['existencia'] - $cantidad;

                        $this->model->actualizarStock($id_producto,$stock);
                    }

                    $vaciar = $this->model->vaciarDetalle('detalle_temp',$id_usuario);

                    if($vaciar == "ok"){
                        $msg = array('msg' => 'ok', 'id_venta' => $id_venta['id_compra']);
                    }

                } else {
                    $msg = "Error al realizar la venta";
                }
            }

        
            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }


        public function generarPDF($id_compra){

            $empresa = $this->model->getEmpresa();

            $producto = $this->model->getProCompra($id_compra);

            require('Libraries/fpdf/fpdf.php');

            $pdf = new FPDF('P','mm',array(110,200));
            $pdf->AddPage();
            $pdf->SetMargins(5,0,0);
            $pdf->SetTitle('Reporte compra');
            $pdf->SetFont($fuente,'B',13);
            $pdf->Cell(70,20,utf8_decode($empresa['nombre']),0,1,'C');
            $pdf->Image(base_url.'Assets/img/logo.png',85,10,20,20);

            //registro unico de contribuyente
            $pdf->SetFont($fuente,'B',11);
            $pdf->Cell(25,5,'RUC: ',0,0,'L');
            $pdf->SetFont($fuente,'',11);
            $pdf->Cell(30,5,utf8_decode($empresa['rfc']),0,1,'L');

            //telefono
            $pdf->SetFont($fuente,'B',11);
            $pdf->Cell(25,5,utf8_decode('Teléfono: '),0,0,'L');
            $pdf->SetFont($fuente,'',11);
            $pdf->Cell(30,5,utf8_decode($empresa['telefono']),0,1,'L');

            //direccion
            $pdf->SetFont($fuente,'B',11);
            $pdf->Cell(25,5,utf8_decode('Dirección: '),0,0,'L');
            $pdf->SetFont($fuente,'',11);
            $pdf->Cell(30,5,utf8_decode($empresa['direccion']),0,1,'L');

            //folio
            $pdf->SetFont($fuente,'B',11);
            $pdf->Cell(25,5,utf8_decode('Folio: '),0,0,'L');
            $pdf->SetFont($fuente,'',11);
            $pdf->Cell(30,5,$id_compra,0,1,'L');
            $pdf->Ln();


            //encabezado de productos
            $pdf->SetFillColor(0,0,0);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(40,5,'Nombre',0,0,'L',true);
            $pdf->Cell(20,5,'Cantidad',0,0,'L',true);
            $pdf->Cell(15,5,utf8_decode('Precio'),0,0,'L',true);
            $pdf->Cell(18,5,utf8_decode('Subtotal'),0,1,'L',true);

            $pdf->SetTextColor(0,0,0);
            $total = 0.00;
            foreach($producto as $row){
                $total = $total + $row['subtotal'];
                $pdf->Cell(40,5,utf8_decode($row['nombre']),0,0,'L');
                $pdf->Cell(20,5,utf8_decode($row['cantidad']),0,0,'L');
                $pdf->Cell(15,5,utf8_decode($row['precio']),0,0,'L');
                $pdf->Cell(18,5,number_format(utf8_decode($row['subtotal']),2,'.',','),0,1,'L');
            }

            $pdf->Ln();
            $pdf->Cell(100,5,utf8_decode('Total a pagar'),0,1,'R');
            $pdf->Cell(100,5,number_format($total,2,'.',','),0,1,'R');



            $pdf->Output();
            
        }

        public function generarPDFVenta($id_venta){

            $empresa = $this->model->getEmpresa();

            $descuento = $this->model->getDescuento($id_venta);

            $producto = $this->model->getProVenta($id_venta);

            // print_r( $producto);

            require('Libraries/fpdf/fpdf.php');

            $pdf = new FPDF('P','mm',array(55,110));

            $fuente = "Courier";

            $pdf->AddPage();
            $pdf->SetMargins(4,0,0);
            $pdf->SetTitle('Reporte venta');
            $pdf->SetFont($fuente,'B',7);
            $pdf->Cell(35,20,utf8_decode($empresa['nombre']),0,1,'C');
            $pdf->Image(base_url.'Assets/img/logo.png',17,3,15,15);
            
            $pdf->SetFont($fuente,'B',6);
            $pdf->Cell(10,5,'Fecha: ',0,0);
            $pdf->SetFont($fuente,'B',8);
            $pdf->Cell(25,5,utf8_decode($producto[0]['fecha']),0,1,'C');

            //telefono
            $pdf->SetFont($fuente,'B',6);
            $pdf->Cell(15,5,utf8_decode('Teléfono: '),0,0,'L');
            $pdf->SetFont($fuente,'B',8);
            $pdf->Cell(25,5,utf8_decode($empresa['telefono']),0,1,'L');

            //direccion
            $pdf->SetFont($fuente,'B',6);
            $pdf->Cell(17,5,utf8_decode('Dirección: '),0,0,'L');
            $pdf->SetFont($fuente,'B',8);
            $pdf->Cell(15,5,utf8_decode($empresa['direccion']),0,1,'L');

            //folio
            $pdf->SetFont($fuente,'B',6);
            $pdf->Cell(25,5,utf8_decode('Folio: '),0,0,'L');
            $pdf->SetFont($fuente,'B',8);
            $pdf->Cell(10,5,$id_venta,0,1,'L');
            
            
            //encabezado de productos
            $pdf->SetFont($fuente,'B',7);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(17,5,'Prod.',0,0,'L',true);
            $pdf->Cell(10,5,'Cant.',0,0,'L',true);
            // $pdf->Cell(30,5,utf8_decode('Descripción'),0,0,'L',true);
            $pdf->Cell(8,5,utf8_decode('Prec.'),0,0,'L',true);
            $pdf->Cell(18,5,utf8_decode('Subt.'),0,1,'L',true);

            $pdf->SetTextColor(0,0,0);
            $total = 0.00;
            $pdf->SetFont($fuente,'B',7);
            foreach($producto as $row){

                $nombre = substr($row['nombre'],0,12);
                $precio = round($row['precio']);

                $total = $total + $row['subtotal'];
                $pdf->Cell(20,5,utf8_decode($nombre),0,0,'L');
                $pdf->Cell(6,5,utf8_decode($row['cantidad']),0,0,'L');
                $pdf->Cell(10,5,utf8_decode($precio),0,0,'L');
                $pdf->Cell(18,5,number_format(utf8_decode($row['subtotal']),2,'.',','),0,1,'L');
            }

            $pdf->Cell(45,5,utf8_decode('Descuento total'),0,1,'R');
            $pdf->Cell(45,5,number_format($descuento['total'],2,'.',','),0,1,'R');
            $pdf->Cell(45,5,utf8_decode('Total a pagar'),0,1,'R');
            $pdf->Cell(45,5,number_format($total,2,'.',','),0,0,'R');
            $pdf->Output();
        }

        public function historial(){

            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'historial_compra');

            if(!empty($verificar)){
                $this->views->getView($this, "historial");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

        
        }

        public function listar_historial(){
            $data = $this->model->getHistorialCompra();

            for ($i=0; $i < count($data); $i++) { 

                if($data[$i]['estado']==1){
                    $data[$i]['estado'] = '<span class="badge badge-success">Completado</span>';
                    
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-warning mb-3" onclick="btnAnularC('.$data[$i]['id_compra'].')"><i class="fas fa-ban"></i></button>
                        <a class="btn btn-danger mb-3" href="'.base_url."Compras/generarPDF/".$data[$i]['id_compra'].'" target="_blank">  <li class="fas fa-file-pdf"></li></a>
                    </div>';
                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Anulado</span>';
                    $data[$i]['acciones'] = '<div>
                        <a class="btn btn-danger mb-3" href="'.base_url."Compras/generarPDF/".$data[$i]['id_compra'].'" target="_blank">  <li class="fas fa-file-pdf"></li></a>
                    </div>';
                }   
           }

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function historialVenta(){

            $id_usuario = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_usuario,'historial_venta');

            if(!empty($verificar)){
                $this->views->getView($this, "historialVenta");
            } else{
                header("Location: ".base_url.'Errors/permisos');
            }

        }

        public function listar_historialVenta(){
            $data = $this->model->getHistorialVenta();

           for ($i=0; $i < count($data); $i++) { 

                if($data[$i]['estado']==1){
                    $data[$i]['estado'] = '<span class="badge badge-success">Completado</span>';
                    
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-warning mb-3" onclick="btnAnularV('.$data[$i]['noFolio'].')"><i class="fas fa-ban"></i></button>
                        <a class="btn btn-danger mb-3" href="'.base_url."Compras/generarPDFVenta/".$data[$i]['noFolio'].'" target="_blank">  <li class="fas fa-file-pdf"></li></a>
                    </div>';
                } else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Anulado</span>';
                    $data[$i]['acciones'] = '<div>
                    <a class="btn btn-danger mb-3" href="'.base_url."Compras/generarPDFVenta/".$data[$i]['noFolio'].'" target="_blank">  <li class="fas fa-file-pdf"></li></a>
                    </div>';
                }   
            }

            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function calcularDescuento($datos){
            
            $array = explode(",",$datos);
            
            $id = $array[0];
            $descuento = $array[1];

            if(empty($id) || empty($descuento)){
                $msg = array('msg' => 'Error', 'icono' => 'error');
            } else {
                $descuentoActual = $this->model->verificarDescuento($id);

                $descuentoTotal = $descuentoActual['descuento'] + $descuento;

                $subtotal = ($descuentoActual['cantidad'] * $descuentoActual['precio'] ) - $descuentoTotal;

                $data = $this->model->actualizarDescuento($descuentoTotal,$subtotal,$id);

                if($data == "ok"){
                    $msg = array('msg' => 'Descuento aplicado', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al aplicar descuento', 'icono' => 'error');
                }

            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function anularCompra($id_compra){

            $data = $this->model->getAnularCompra($id_compra);

            $anular = $this->model->getAnular($id_compra);

            foreach($data as $row){
                $this->model->actualizarStock($row['cantidad'],$row['id_producto']);

                $stock_actual = $this->model->getProductoInventario($row['id_producto']);

                $stock = $stock_actual['existencia'] - $row['cantidad'];

                $this->model->actualizarStock($row['id_producto'],$stock);
            }

            if($anular == "ok"){
                $msg = array('msg'=>'Compra anulada','icono' => 'success');
            } else {
                $msg = array('msg'=>'Error al anular','icono' => 'error');              
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();

        }

        public function anularVenta($noFolio){

            $data = $this->model->getAnularVenta($noFolio);

            $anular = $this->model->getAnularV($noFolio);

            foreach($data as $row){

                $stock_actual = $this->model->getProductoInventario($row['id_producto']);

                $stock = $stock_actual['existencia'] + $row['cantidad'];

                $this->model->actualizarStock($row['id_producto'],$stock);
            }

            if($anular == "ok"){
                $msg = array('msg'=>'Venta anulada','icono' => 'success');
            } else {
                $msg = array('msg'=>'Error al anular','icono' => 'error');              
            }

            echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            die();

        }
    }


?>