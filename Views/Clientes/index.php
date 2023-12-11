<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Clientes</li>
    </ol>

    <button class="btn btn-primary" type="button" onclick="frmClientes();" ><li class="fas fa-plus"></li> </button>
    
    <div class="table-responsive">
        <table id="tblClientes" class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>RFC</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="nuevo_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="my-modal-title">Nuevo cliente</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form method="post" id="frmClientes">
                        <div class="form-group">
                            <label for="rfc">RFC</label>
                            <input type="hidden" id="id_cliente" name="id_cliente">
                            <input id="rfc" class="form-control" type="text" name="rfc" placeholder="RFC">
                        </div>
         
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del cliente">
                        </div>
                                                   
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input id="direccion" class="form-control" type="text" name="direccion" placeholder="Dirección">
                        </div>
                     
                        <div class="form-group">
                            <label for="correo">Correo electrónico</label>
                            <input id="correo" class="form-control" type="email" name="correo" placeholder="Correo">
                        </div>
                                         
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input id="telefono" class="form-control" type="text" name="telefono" placeholder="Teléfono" maxlength="10">
                        </div>
                            
                        <button class="btn btn-primary" id="btnAccion" type="button" onclick="registrarCliente(event);">Registrar</button>
                        <button class="btn btn-danger" id="btnAccion" type="button" data-dismiss="modal">Cancelar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

   

<?php include "Views/Templates/footer.php"?>