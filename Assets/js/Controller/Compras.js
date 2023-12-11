function buscarCodigo(e){
    e.preventDefault();

    const idinventario = document.getElementById("idinventario").value;

    if(idinventario != ''){
        if(e.which == 13){
    
            const url = base_url + "Compras/buscarCodigo/"+idinventario;
            
            fetch(url, {
                        method: 'GET',
            })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function (texto) {
                
                let texto1 = texto.trim();
                const res = JSON.parse(texto1);
    
                if(res){
                    document.getElementById("nombre").value = res.descripcion;
                    document.getElementById("precio").value = res.precioEntrada;
                    document.getElementById("cantidad").removeAttribute('disabled');
                    document.getElementById("cantidad").focus();

                } else {
                    alertas('El producto no existe','warning');
                    document.getElementById("idinventario").value = '';
                    document.getElementById("idinventario").focus();
                }
    
                
            })
            .catch(function (err) {
                console.log(err);
            });
        }
    } else {
        alertas('Ingrese el código','warning');
    }
}

function calcularPrecioVenta(e){
    e.preventDefault();

    const cantidad = document.getElementById("cantidad").value;
    const precio = document.getElementById("precio").value;

    document.getElementById("subtotal").value = precio * cantidad;

    //si la tecla que pulso es ENTER o 13
    if(e.which == 13){
        if(cantidad > 0){

            const url = base_url + "Compras/ingresarVenta/";
            const frmCompra = document.getElementById("frmVenta");
            const formulario = new FormData(frmCompra);
            
            fetch(url, {
                method: 'POST',
                body: formulario
            })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function (texto) {                

                const res = JSON.parse(texto);
               
                alertas(res.msg,res.icono);
                frmCompra.reset();
                cargarDetalleVenta();

                document.getElementById('cantidad').setAttribute('disabled','disabled');
                document.getElementById('idinventario').focus();
            })
            .catch(function (err) {
                console.log(err);
            });
        }
    }
}

function calcularPrecio(e){
    e.preventDefault();

    const cantidad = document.getElementById("cantidad").value;
    const precio = document.getElementById("precio").value;

    document.getElementById("subtotal").value = precio * cantidad;

    //si la tecla que pulso es ENTER o 13
    if(e.which == 13){
        if(cantidad > 0){

            const url = base_url + "Compras/ingresar/";
            const frmCompra = document.getElementById("frmCompra");
            const formulario = new FormData(frmCompra);
            
            fetch(url, {
                method: 'POST',
                body: formulario
            })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function (texto) {
                
                let texto1 = texto.trim();

                const res = JSON.parse(texto1);
                if(res == "ok"){
                    alertas('Producto ingresado correctamente','success');
                    frmCompra.reset();
                    cargarDetalle();
                } else if (res=="modificado"){
                    alertas('Producto modificado correctamente','success');
                    frmCompra.reset();
                    cargarDetalle();
                }

                document.getElementById('cantidad').setAttribute('disabled','disabled');
                document.getElementById('idinventario').focus();
            })
            .catch(function (err) {
                console.log(err);
            });
        }
    }
}

if(document.getElementById('tblDetalle')){
    cargarDetalle();
}

if(document.getElementById('tblDetalleVenta')){
    cargarDetalleVenta();
}

function cargarDetalle(){

    const url = base_url + "Compras/listar/detalle";
            
    fetch(url, {
        method: 'GET',
    })
    .then(function (response) {
        if (response.ok) {
            return response.text();
        } else {
            throw "Error en la llamada Ajax";
        }
    })
    .then(function (texto) {
                
        let texto1 = texto.trim();

        const res = JSON.parse(texto1);

        let html = '';

        res.detalle.forEach(row => {
            html += `<tr>
                <td>${row['id_detalle']}</td>
                <td>${row['descripcion']}</td>
                <td>${row['cantidad']}</td>
                <td>${row['precio']}</td>
                <td>${row['subtotal']}</td>
                <td>
                    <button class="btn btn-danger" type="button" onclick="deleteDetalle(${row['id_detalle']},1);">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
        });

        document.getElementById("tblDetalle").innerHTML = html;
        document.getElementById("total").value = res.subtotal.total;

                
    })
    .catch(function (err) {
        console.log(err);
    });

}

function cargarDetalleVenta(){

    const url = base_url + "Compras/listar/detalle_temp";
            
    fetch(url, {
        method: 'GET',
    })
    .then(function (response) {
        if (response.ok) {
            return response.text();
        } else {
            throw "Error en la llamada Ajax";
        }
    })
    .then(function (texto) {
                
        let texto1 = texto.trim();

        const res = JSON.parse(texto1);

        let html = '';

        res.detalle.forEach(row => {
            html += `<tr>
                <td>${row['id_detalle']}</td>
                <td>${row['descripcion']}</td>
                <td>${row['cantidad']}</td>
                <td><input class="form-control" placeholder="Descuento" type="text" onkeyup="calcularDescuento(event,${row['id_detalle']})"></td>
                <td>${row['descuento']}</td>
                <td>${row['precio']}</td>
                <td>${row['subtotal']}</td>
                <td>
                    <button class="btn btn-danger" type="button" onclick="deleteDetalle(${row['id_detalle']},2);">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
        });

        document.getElementById("tblDetalleVenta").innerHTML = html;
        document.getElementById("total").value = res.subtotal.total;         
    })
    .catch(function (err) {
        console.log(err);
    });

}

function calcularDescuento(e,id){
    e.preventDefault();

    if(e.target.value == ''){
        alertas('Ingrese el descuento','warning');
    } else {

        if(e.which == 13){

            const url =  base_url + "Compras/calcularDescuento/" +id + "/"+e.target.value;

            fetch(url, {
                method: 'GET',
            })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function (texto) {
                        
                const res = JSON.parse(texto);
                alertas(res.msg,res.icono);
                cargarDetalleVenta();
                        
            })
            .catch(function (err) {
                console.log(err);
            });
        }
    }
}

function deleteDetalle(id_detalle, accion){

    let url;

    if(accion == "1"){
        url =  base_url + "Compras/delete/"+id_detalle;
    } else {
        url =  base_url + "Compras/deleteVenta/"+id_detalle;
    } 
            
    fetch(url, {
        method: 'GET',
    })
    .then(function (response) {
        if (response.ok) {
            return response.text();
        } else {
            throw "Error en la llamada Ajax";
        }
    })
    .then(function (texto) {
                
        let texto1 = texto.trim();

        const res = JSON.parse(texto1);

        if(res == "ok"){
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Producto eliminado',
                showConfirmButton: false,
                timer:3000
            })
        } else {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Error al eliminar producto',
                showConfirmButton: false,
                timer:3000
            })
        }

        if(accion == "1"){
            cargarDetalle();  
        } else {
            cargarDetalleVenta();
        } 
                
    })
    .catch(function (err) {
        console.log(err);
    });

}

function procesar(accion){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })


    let mensaje = "";

    if(accion == 1){
        mensaje = "¿Está seguro de realizar la compra?";
    } else {
        mensaje = "¿Está seguro de realizar la venta?"
    }

    swalWithBootstrapButtons.fire({
        title: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            let url; 
            if(accion == 1){
                url = base_url + "Compras/registrarCompra/";
                
                fetch(url, {
                    method: 'GET',
                })
                .then(function (response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw "Error en la llamada Ajax";
                    }
                })
                .then(function (textoData) {
                    //quitar espacios vacios 
                    let texto1 = textoData.trim();

                    console.log(texto1);

                    //convertir un string a objeto
                    let texto = JSON.parse(texto1)

                    if (texto.msg =="ok"){

                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            'Compra generada',
                            'success'
                        )

                        let ruta;

                        ruta = base_url + 'Compras/generarPDF/'+texto.id_compra;

                        window.open(ruta);
                        setTimeout(()=>{
                            window.location.reload();
                        },3000);
                    } else {
                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            texto.msg,
                            texto.icono
                        )
                    }

                })
                .catch(function (err) {
                    console.log(err);
                });

            } else {
                const id_cliente = document.getElementById('cliente').value;
                url = base_url + "Compras/registrarVenta/"+id_cliente;
                console.log(url);

                if(id_cliente != ''){
                    fetch(url, {
                        method: 'GET',
                    })
                    .then(function (response) {
                        if (response.ok) {
                            return response.text();
                        } else {
                            throw "Error en la llamada Ajax";
                        }
                    })
                    .then(function (textoData) {
                        //quitar espacios vacios 
                        let texto1 = textoData.trim();
    
                        console.log(texto1);
    
                        //convertir un string a objeto
                        let texto = JSON.parse(texto1)
    
                        if (texto.msg =="ok"){
    
                            swalWithBootstrapButtons.fire(
                                'Mensaje',
                                'Venta generada',
                                'success'
                            )
    
                            let ruta;
    
                            if(accion == 1){
                                ruta = base_url + 'Compras/generarPDF/'+texto.id_compra;
                            } else {
                                ruta = base_url + 'Compras/generarPDFVenta/'+texto.id_venta;
                            }
    
                            window.open(ruta);
                            setTimeout(()=>{
                                window.location.reload();
                            },3000);
                        } else {
                            swalWithBootstrapButtons.fire(
                                'Mensaje',
                                texto.msg,
                                texto.icono
                            )
                        }
    
                    })
                    .catch(function (err) {
                        console.log(err);
                    });
                } else{
                    alertas("Ingrese un cliente","warning");
                }
            }

            

            

            
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Operación cancelada',
                'El Producto sigue inactivo',
                'error'
            )
        }
    })

}

let tbl_historial_compra;

document.addEventListener("DOMContentLoaded", function () {

    $('#cliente').select2();

    tbl_historial_compra = $('#t_historial_compra').DataTable({
        ajax: {
            url: base_url + "Compras/listar_historial",
            dataSrc: ''
        },
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        columns: [
            {
                data: 'id_compra',
            },
            {
                data: 'total',
            },
            {
                data: 'fecha',
            },
            {
                data: 'estado',
            },
            {
                data: 'acciones'
            }
        ]
    })
})

function buscarCodigoVenta(e){
    e.preventDefault();

    const idinventario = document.getElementById("idinventario").value;

    if(idinventario != ''){
        if(e.which == 13){
    
            const url = base_url + "Compras/buscarCodigo/"+idinventario;
            
            fetch(url, {
                        method: 'GET',
            })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function (texto) {
                
                let texto1 = texto.trim();
                const res = JSON.parse(texto1);
    
                if(res){
                    document.getElementById("nombre").value = res.descripcion;
                    document.getElementById("precio").value = res.precioSalida;
                    document.getElementById("cantidad").removeAttribute('disabled');
                    document.getElementById("cantidad").focus();

                } else {
                    alertas('El producto no existe','warning');
                    document.getElementById("idinventario").value = '';
                    document.getElementById("idinventario").focus();
                }
    
                
            })
            .catch(function (err) {
                console.log(err);
            });
        }
    } else {
        alertas('Ingrese el código','warning');
    }
}

let tbl_historial_venta;

document.addEventListener("DOMContentLoaded", function () {

    $('#cliente').select2();
    $('#pro').select2();

    tbl_historial_venta = $('#t_historial_venta').DataTable({
        ajax: {
            url: base_url + "Compras/listar_historialVenta",
            dataSrc: ''
        },
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        columns: [
            {
                data: 'noFolio',
            },
            {
                data: 'totalVenta',
            },
            {
                data: 'fechaVenta',
            },
            {
                data: 'estado',
            },
            {
                data: 'acciones'
            }
        ]
    })
})



function btnAnularC(id_compra){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de anular la compra?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Compras/anularCompra/"+id_compra;
            
            fetch(url, {
                method: 'GET',
            })
            .then(function (response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw "Error en la llamada Ajax";
                    }
            })
            .then(function (texto) {

                const res = JSON.parse(texto)

                alertas(res.msg,res.icono)

                tbl_historial_compra.ajax.reload();
                    

            })
            .catch(function (err) {
                console.log(err);
            });

            
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Operación cancelada',
                'El Producto sigue inactivo',
                'error'
            )
        }
    })
}

function btnAnularV(noFolio){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de anular la venta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Compras/anularVenta/"+noFolio;
            
            fetch(url, {
                method: 'GET',
            })
            .then(function (response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw "Error en la llamada Ajax";
                    }
            })
            .then(function (texto) {

                console.log(texto)

                const res = JSON.parse(texto)

                alertas(res.msg,res.icono)

                tbl_historial_venta.ajax.reload();
                    

            })
            .catch(function (err) {
                console.log(err);
            });

            
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Operación cancelada',
                'El Producto sigue inactivo',
                'error'
            )
        }
    })
}