<?php include "Views/Templates/header.php"?>

    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header text-center bg-primary text-white">
                    Asignar permisos
            </div>
            <div class="card-body">
                <form id="formulario" onsubmit="registrarPermisos(event);">
                    <?php foreach($data['datos'] as $row) { ?>
                        <label for=""><?php echo $row['permiso']?></label>
                        <input type="checkbox" name="permisos[]" value="<?php echo $row['id_permiso']?>" <?php echo isset($data['asignados'][$row['id_permiso']]) ? 'checked' : ''?> ><br>
                    <?php } ?>
                    
                    <input type="hidden" value=<?php echo $data['id_usuario']?> name="id_usuario">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">Asignar permisos</button>
                        <a class="btn btn-outline-danger" href="<?php echo base_url?>Usuarios">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include "Views/Templates/footer.php"?>