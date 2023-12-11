<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Usuarios</li>
    </ol>

    <button class="btn btn-primary" type="button" onclick="frmUsuario();" ><li class="fas fa-plus"></li> </button>
    
    <div class="table-responsive">
        <table id="tblUsuarios" class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>RFC</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Caja</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="my-modal-title">Nuevo usuario</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                    <form method="post" id="frmUsuario">
                        <div class="form-group">
                            <label for="rfc">RFC</label>
                            <input type="hidden" id="id_usuario" name="id_usuario">
                            <input id="rfc" class="form-control" type="text" name="rfc" placeholder="RFC">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usuario">Usuario</label>
                                    <input id="usuario" class="form-control" type="text" name="usuario" placeholder="Usuario" maxlength="10">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="claves">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input id="password" class="form-control" type="password" name="password" placeholder="Contraseña" maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmar">Confirmar contraseña</label>
                                    <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="Confirmar contraseña" maxlength="10">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input id="telefono" class="form-control" type="text" name="telefono" placeholder="Teléfono" maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="correo">Correo electrónico</label>
                                    <input id="correo" class="form-control" type="email" name="correo" placeholder="Correo">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="caja">Caja</label>
                            <select id="caja" class="form-control" name="caja">
                                <?php foreach($data['cajas'] as $row){ ?>
                                    <option value="<?php echo $row['id']?>"><?php echo $row['caja']?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <button class="btn btn-primary" id="btnAccion" type="button" onclick="registrarUser(event);">Registrar</button>
                        <button class="btn btn-danger" id="btnAccion" type="button" data-dismiss="modal">Cancelar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

   

<?php include "Views/Templates/footer.php"?>