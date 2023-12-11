<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Ganancia del producto semanal</li>
    </ol>

    <form method="post" id="frmGananciaMes">
        <div class="row">
            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label for="date">Calendario</label>
                    <input id="date" class="form-control" type="date" name="date">
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label for="date2">Calendario</label>
                    <input id="date2" class="form-control" type="date" name="date2">
                </div>
            </div>
                
        </div>
        
        <button class="btn btn-primary" id="btnAccion" type="button" onclick="buscarFechaMes(event);">Buscar</button>
    </form>
    
    <br>
    
    <div class="table-responsive">
        <table class="table table-hover display responsive no-wrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Precio compra</th>
                    <th>Precio venta</th>
                    <th>Ganancia</th>
                    <th>Productos vendidos</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="tblGanancia">
                

            </tbody>
        </table>

        <div class="float-right">
            <label>Total de ganancia</label>
            <input class="border text-center" id="totalGanancia" type="text" disabled>
        </div>
        
    </div>
   

<?php include "Views/Templates/footer.php"?>