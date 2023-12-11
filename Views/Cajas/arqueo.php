<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Arqueo de caja</li>
    </ol>

    <button class="btn btn-primary mb-1" type="button" onclick="arqueoCaja();" ><li class="fas fa-plus"></li> </button>
    <button class="btn btn-warning mb-1" type="button" onclick="cerrarCaja();" >Cerrar caja</button>
    
    <div class="table-responsive">
        <table id="tblArqueo" class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Id Caja</th>
                    <th>Monto inicial</th>
                    <th>Monto final</th>
                    <th>Fecha apertura</th>
                    <th>Fecha cierre</th>
                    <th>Total ventas</th>
                    <th>Monto total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="abrir_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="my-modal-title">Arqueo caja</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form id="frmAbrirCaja" onsubmit="abrirArqueo(event);">
                        <div class="form-group">
                            <label for="monto_inicial">Monto inicial</label>
                            <input type="hidden" id="id_cierre_caja" name="id_cierre_caja">
                            <input id="monto_inicial" class="form-control" type="text" name="monto_inicial" placeholder="Monto inicial">
                        </div>

                        <div id="ocultar_campos">

                            <div class="form-group">
                                <label for="monto_final">Monto total</label>
                                <input id="monto_final" class="form-control" type="text" disabled>
                            </div>

                            <div class="form-group">
                                <label for="total_ventas">Total de ventas</label>
                                <input id="total_ventas" class="form-control" type="text" disabled>
                            </div>

                            <div class="form-group">
                                <label for="monto_general">Monto general</label>
                                <input id="monto_general" class="form-control" type="text">
                            </div>

                        </div>

                        <button class="btn btn-primary" id="btnAccion" type="submit"></button>
                        <button class="btn btn-danger" id="btnAccion" type="button" data-dismiss="modal">Cancelar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

   

<?php include "Views/Templates/footer.php"?>