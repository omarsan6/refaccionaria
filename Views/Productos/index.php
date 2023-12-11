<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Productos</li>
    </ol>   

    <button class="btn btn-primary" type="button" onclick="frmProducto();" ><li class="fas fa-plus"></li> </button>
    
    <div class="table-responsive">
        <table id="tblProductos" class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Id del producto</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio salida (venta)</th>
                    <th>Descripción</th>
                    <th>Existencia</th>
                    <th>Fabricante</th>
                    <th>Categoria</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="my-modal-title">Nuevo Producto</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form method="post" id="frmProducto">
                        
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="hidden" id="idinventario" name="idinventario">
                            <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="precioEntrada">Precio entrada</label>
                                    <input id="precioEntrada" class="form-control" type="text" name="precioEntrada" placeholder="Precio entrada" maxlength="10">
                                </div>
                            </div>
                        

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="precioSalida">Precio salida</label>
                                    <input id="precioSalida" class="form-control" type="text" name="precioSalida" placeholder="Precio salida" maxlength="10">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input id="descripcion" class="form-control" type="text" name="descripcion" placeholder="Descripción">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabricante">Fabricante</label>
                                    <input id="fabricante" class="form-control" type="text" name="fabricante" placeholder="Fabricante" maxlength="45">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <select id="categoria" class="form-control" name="categoria">
                                        <?php foreach($data['categoria'] as $row){ ?>
                                            <option value="<?php echo $row['idcategoria']?>"><?php echo $row['nombre']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Foto</label>
                            <div class="card border-primary">
                                <div class="card-body">
                                    <label for="foto" width="100%" id="icon-image" class="btn btn-primary"><i class="fas fa-image"></i></label>
                                    <span id="icon-cerrar"></span>
                                    <input id="foto" class="d-none" type="file" name="foto" onchange="preview(event);">
                                    <input type="hidden" id="fotoActual" name="fotoActual">
                                    <img class="img-thumbmail" id="img-preview" width="100">
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary" id="btnAccion" type="button" onclick="registrarProducto(event);">Registrar</button>
                        <button class="btn btn-danger" id="btnAccion" type="button" data-dismiss="modal">Cancelar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

   

<?php include "Views/Templates/footer.php"?>