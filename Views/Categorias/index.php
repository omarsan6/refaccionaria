<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Categorias</li>
    </ol>

    <button class="btn btn-primary mb-1" type="button" onclick="frmCategoria();" ><li class="fas fa-plus"></li> </button>
    
    <div class="table-responsive">
        <table id="tblCategorias" class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Id categoria</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="nuevo_categoria" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="my-modal-title">Nueva categoria</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form method="post" id="frmCategoria">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="hidden" id="idcategoria" name="idcategoria">
                            <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre">
                        </div>
         
                        <div class="form-group">
                            <label for="descripcion">Desccripción</label>
                            <input id="descripcion" class="form-control" type="text" name="descripcion" placeholder="Descripción">
                        </div>
                            
                        <button class="btn btn-primary" id="btnAccion" type="button" onclick="registrarCategoria(event);">Registrar</button>
                        <button class="btn btn-danger" id="btnAccion" type="button" data-dismiss="modal">Cancelar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

   

<?php include "Views/Templates/footer.php"?>