<?php include "Views/Templates/header.php"?>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex">
                    Usuarios
                    <i class="fas fa-user fa-2x ml-auto"></i>
                </div>
                <div class="card-footer d-flex aling-items-center justify-content-between">
                    <a href="<?php echo base_url; ?>Usuarios">Ver detalle</a>
                    <span><?php echo $data['usuario']['total'] ?></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success">
                <div class="card-body d-flex text-white">
                    Clientes
                    <i class="fas fa-users fa-2x ml-auto"></i>
                </div>
                <div class="card-footer d-flex aling-items-center justify-content-between text-white">
                    <a href="<?php echo base_url; ?>Clientes" class="text-white">Ver detalle</a>
                    <span><?php echo $data['cliente']['total'] ?></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger">
                <div class="card-body d-flex text-white">
                    Productos
                    <i class="fab fa-product-hunt fa-2x ml-auto"></i>
                </div>
                <div class="card-footer d-flex aling-items-center justify-content-between">
                    <a href="<?php echo base_url; ?>Usuarios" class="text-white">Ver detalle</a>
                    <span class="text-white"><?php echo $data['inventario']['total'] ?></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning">
                <div class="card-body d-flex text-white">
                    Ventas por dia
                    <i class="fas fa-cash-register fa-2x ml-auto"></i>
                </div>
                <div class="card-footer d-flex aling-items-center justify-content-between">
                    <a href="<?php echo base_url; ?>Compras/historialVenta" class="text-white">Ver detalle</a>
                    <span class="text-white"><?php echo $data['venta']['total'] ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Productos con Stock mínimo
                </div>
                <div class="card-body">
                    <canvas id="stockMinimo" width="600" height="600"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Productos más vendidos
                </div>
                <div class="card-body">
                    <canvas id="productosMasVendidos" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

<?php include "Views/Templates/footer.php"?>