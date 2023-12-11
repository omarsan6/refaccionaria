<?php include "Views/Templates/header.php"?>

    <ol class="breadcrumb mb-4 text-center">
        <li class="breadcrumb-item active">Ganancia del dia por producto</li>
    </ol>

    <form method="post" id="frmGananciaDia">
        <div class="form-group">
            <label for="date">Calendario</label>
            <input id="date" class="form-control" type="date" name="date">
        </div>
        <button class="btn btn-primary" id="btnAccion" type="button" onclick="buscarFecha(event);">Buscar</button>
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