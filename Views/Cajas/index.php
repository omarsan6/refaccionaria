<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Cajas</li>
    </ol>

    <button class="btn btn-primary mb-1" type="button" onclick="frmCliente();" ><li class="fas fa-plus"></li> </button>
    
    <div class="table-responsive">
        <table id="tblCajas" class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Id Caja</th>
                    <th>Nombre caja</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="nuevo_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="my-modal-title">Nueva caja</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form method="post" id="frmCaja">
                        <div class="form-group">
                            <label for="rfc">Nombre de caja</label>
                            <input type="hidden" id="id" name="id">
                            <input id="caja" class="form-control" type="text" name="caja" placeholder="Caja">
                        </div>
         
                        <div class="form-group">
                            <label for="nombre">Descripción</label>
                            <input id="descripcion" class="form-control" type="text" name="descripcion" placeholder="Descripción" maxlength="100">
                        </div>
                            
                        <button class="btn btn-primary" id="btnAccion" type="button" onclick="registrarCaja(event);">Registrar</button>
                        <button class="btn btn-danger" id="btnAccion" type="button" data-dismiss="modal">Cancelar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

   

<?php include "Views/Templates/footer.php"?>