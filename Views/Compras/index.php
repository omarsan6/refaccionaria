<?php include "Views/Templates/header.php" ?>

<div class="card-header bg-primary text-white">
    <div class="d-flex justify-content-between align-items-center">
        <h4>Nueva compra</h4>

        <div class="d-flex justify-content-center align-items-center">
        <div class="">
            <label for="cliente">Buscar producto</label>
            <select id="cliente" class="form-control" name="cliente">
                <?php foreach ($data as $row) { ?>
                    <option value="<?php echo $row['idinventario'] ?>"> <?php echo $row['idinventario'] ?>. <?php echo $row['nombre'] ?></option>
                <?php } ?>
            </select>
        </div>
        </div>

    </div>
</div>



<div class="card">
    <div class="card-body">
        <form id="frmCompra">

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="idinventario">Id Inventario</label>
                        <input id="idinventario" class="form-control" type="text" name="idinventario" placeholder="Id inventario" onkeyup="buscarCodigo(event);">
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="nombre">Descripción</label>
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Descripción del producto" disabled>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input id="cantidad" class="form-control" type="number" name="cantidad" placeholder="Cantidad" onkeyup="calcularPrecio(event);" disabled>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input id="precio" class="form-control" type="number" name="precio" placeholder="Precio" disabled>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="subtotal">Subtotal</label>
                        <input id="subtotal" class="form-control" type="number" name="subtotal" placeholder="Subtotal" disabled>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>

<table class="table table-light table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th>Id</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody id="tblDetalle">

    </tbody>
</table>

<div class="row">
    <div class="col-md-4 ml-auto">

        <div class="col-md-12">
            <div class="form-group">
                <label for="total">Total</label>
                <input id="total" class="form-control" type="text" name="total" placeholder="Total" disabled>
                <button class="btn btn-primary mt-2 btn-block" type="button" onclick="procesar(1);">Generar compra</button>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php" ?>