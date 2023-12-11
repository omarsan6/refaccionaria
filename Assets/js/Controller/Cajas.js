let tblCajas;

document.addEventListener("DOMContentLoaded", function () {
    tblCajas = $('#tblCajas').DataTable({
        ajax: {
            url: base_url + "Cajas/listar",
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
                data: 'id',
            },
            {
                data: 'caja',
            },
            {
                data: 'descripcion',
            },
            {
                data: 'estado'
            },
            {
                data: 'acciones'
            }
        ]
    })

})


function frmCliente() {
    document.getElementById("my-modal-title").textContent = "Agregar Caja";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmCaja").reset();
    document.getElementById("id").value = '';

    $("#nuevo_caja").modal("show");
}

function registrarCaja(e) {
    e.preventDefault();

    let caja = document.getElementById("caja");

    let descripcion = document.getElementById("descripcion");

    let id = document.getElementById("id");

    if (caja.value == "" || descripcion.value == "") {

        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3500
        })

    } else {

        const frm = document.getElementById("frmCaja");

        const url = base_url + "Cajas/registrar";

        //pasar las variables a traves de URLSearch
        const data = new URLSearchParams("caja=" + caja.value + "&descripcion=" + descripcion.value
             + "&id=" + id.value);

        fetch(url, {
            method: 'POST',
            body: data
        })
            .then(function (response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw "Error en la llamada Ajax";
                }
            })
            .then(function (texto) {

                let textoSinComilla = texto.replace(/['"]+/g, '');

                textoSinComilla = textoSinComilla.trim();

                if (textoSinComilla === "si") {

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Caja registrado correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })

                    frm.reset();
                    $("#nuevo_caja").modal("hide");
                    tblCajas.ajax.reload();

                } else if (textoSinComilla == "modificado") {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Caja modificada correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })
                    $("#nuevo_cliente").modal("hide");
                    tblCajas.ajax.reload();
                } else {

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: textoSinComilla,
                        showConfirmButton: false,
                        timer: 7500
                    })
                }

            })
            .catch(function (err) {
                console.log(err);
            });

    }
}

function btnEditarCaja(id) {

    document.getElementById("my-modal-title").textContent = "Editar Caja";
    document.getElementById("btnAccion").textContent = "Modificar";

    const url = base_url + "Cajas/editar/" + id;

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

            //convertir un string a objeto
            let texto = JSON.parse(texto1)

            document.getElementById("id").value = texto.id;
            document.getElementById("caja").value = texto.caja;
            document.getElementById("descripcion").value = texto.descripcion;
        })
        .catch(function (err) {
            console.log(err);
        });

    $("#nuevo_caja").modal("show");
}

function btnEliminarCaja(id) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de eliminar?',
        text: "La caja no se eliminará permanentemente, solo cambiará a estado 'Inactivo'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Cajas/eliminar/" + id;

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
                    //convertir un string a objeto
                    let texto = JSON.parse(texto1)

                    if (texto =="ok"){
                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            'Caja eliminado con éxito',
                            'success'
                        )
                        tblCajas.ajax.reload();
                    } else {
                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            texto,
                            'error'
                        )
                    }

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
                'La caja sigue activa',
                'error'
            )
        }
    })
}

function btnActivarCaja(id) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de activar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Cajas/activar/" + id;

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
                    //convertir un string a objeto
                    let texto = JSON.parse(texto1)

                    if (texto =="ok"){
                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            'Caja activada con éxito',
                            'success'
                        )
                        tblCajas.ajax.reload();
                    } else {
                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            texto,
                            'error'
                        )
                    }

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
                'El Cliente sigue inactivo',
                'error'
            )
        }
    })
}

function arqueoCaja(){
    document.getElementById('monto_inicial').value = '';
    document.getElementById('btnAccion').textContent = 'Abrir caja';
    document.getElementById('ocultar_campos').classList.add('d-none');
    $('#abrir_caja').modal('show');
}

let tblArqueo;

document.addEventListener("DOMContentLoaded", function () {
    tblArqueo = $('#tblArqueo').DataTable({
        ajax: {
            url: base_url + "Cajas/listarArqueo",
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
                data: 'id_cierre_caja',
            },
            {
                data: 'monto_inicial',
            },
            {
                data: 'monto_final'
            },
            {
                data: 'fecha_apertura'
            },
            {
                data: 'fecha_cierre'
            },
            {
                data: 'total_ventas'
            },
            {
                data: 'monto_total'
            },
            {
                data: 'estado'
            }
        ]
    })

})

function abrirArqueo(e){
    e.preventDefault();

    const monto_inicial = document.getElementById('monto_inicial').value;

    if(monto_inicial == ''){
        alertas('Ingrese el monto inicial','warning');
    } else {

        const frm = document.getElementById('frmAbrirCaja');

        const form = new FormData(frm);

        const url = base_url + "Cajas/abrirArqueo"

        fetch(url, {
            method: 'POST',
            body: form
        })
        .then(function (response) {
            if (response.ok) {
                return response.text();
            } else {
                throw "Error en la llamada Ajax";
            }
            })
        .then(function (texto) {
            console.log(texto);
            const res = JSON.parse(texto);
            alertas(res.msg,res.icono);
            tblArqueo.ajax.reload();
            $('#abrir_caja').modal('hide');

        })
        .catch(function (err) {
            console.log(err);
        });
    }
}

function cerrarCaja(){

    const url = base_url + "Cajas/getVentas";
            
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

        document.getElementById('monto_inicial').value = res.monto_inicial.monto_inicial;
        document.getElementById('monto_final').value = res.monto_total.total;
        document.getElementById('total_ventas').value = res.total_ventas.total;
        document.getElementById('monto_general').value = res.monto_general;
        document.getElementById('id_cierre_caja').value = res.monto_inicial.id_cierre_caja;


        document.getElementById('ocultar_campos').classList.remove('d-none');
        document.getElementById('btnAccion').textContent = 'Cerrar caja';


        $('#abrir_caja').modal('show');                

    })
    .catch(function (err) {
        console.log(err);
    });


    // const swalWithBootstrapButtons = Swal.mixin({
    //     customClass: {
    //         confirmButton: 'btn btn-success mr-3',
    //         cancelButton: 'btn btn-danger'
    //     },
    //     buttonsStyling: false
    // })

    // swalWithBootstrapButtons.fire({
    //     title: '¿Está seguro de cerrar la caja?',
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonText: 'Si',
    //     cancelButtonText: 'No',

    // }).then((result) => {
    //     if (result.isConfirmed) {

    //         const url = base_url + "Cajas/cerrar/";
            
    //         fetch(url, {
    //             method: 'GET',
    //         })
    //         .then(function (response) {
    //                 if (response.ok) {
    //                     return response.text();
    //                 } else {
    //                     throw "Error en la llamada Ajax";
    //                 }
    //         })
    //         .then(function (texto) {

    //             const res = JSON.parse(texto)

    //             alertas(res.msg,res.icono)

    //             tblArqueo.ajax.reload();
                    

    //         })
    //         .catch(function (err) {
    //             console.log(err);
    //         });

            
    //     } else if (
    //         /* Read more about handling dismissals below */
    //         result.dismiss === Swal.DismissReason.cancel
    //     ) {
    //         swalWithBootstrapButtons.fire(
    //             'Operación cancelada',
    //             'El Producto sigue inactivo',
    //             'error'
    //         )
    //     }
    // })
}


// ################################################## //

function buscarFecha(e){
    e.preventDefault();
    
    const frm = document.getElementById("frmGananciaDia");

    const url = base_url + "Cajas/buscarProductoDia";

    const datosForm = new FormData(frm);
    
    
    fetch(url, {
        method: 'POST',
        body: datosForm
    })
    .then(function (response) {
        if (response.ok) {
            return response.text();
        } else {
            throw "Error en la llamada Ajax";
        }
    })
    .then(function (texto) {

        console.log(texto);

        const res = JSON.parse(texto);

        if(res == ""){
            alertas("No hay productos vendidos ese dia","warning");
        } else {

            let html = '';
            let total = 0;

            res.forEach(row => {
                html += `<tr>
                    <td>${row['nombre']}</td>
                    <td>${row['precioEntrada']}</td>
                    <td>${row['precioSalida']}</td>
                    <td>${row['ganancia']}</td>
                    <td>${row['cantidad']}</td>
                    <td>${row['subtotal']}</td>
                    <td>${row['descuento']}</td>
                    <td>${row['total']}</td>
                </tr>`;

                total += Math.floor(row['total']);
            });

            document.getElementById("tblGanancia").innerHTML = html;
            document.getElementById("totalGanancia").value = total;
        }

    })
    .catch(function (err) {
        console.log(err);
    });

}

function buscarFechaMes(e){
    e.preventDefault();
    
    const frm = document.getElementById("frmGananciaMes");

    const url = base_url + "Cajas/buscarProductoMes";

    const datosForm = new FormData(frm);
    
    
    fetch(url, {
        method: 'POST',
        body: datosForm
    })
    .then(function (response) {
        if (response.ok) {
            return response.text();
        } else {
            throw "Error en la llamada Ajax";
        }
    })
    .then(function (texto) {

        console.log(texto);

        const res = JSON.parse(texto);

        if(res == ""){
            alertas("No hay productos vendidos ese dia","warning");
        } else {

            let html = '';
            let total = 0;

            res.forEach(row => {
                html += `<tr>
                    <td>${row['nombre']}</td>
                    <td>${row['precioEntrada']}</td>
                    <td>${row['precioSalida']}</td>
                    <td>${row['ganancia']}</td>
                    <td>${row['cantidad']}</td>
                    <td>${row['subtotal']}</td>
                    <td>${row['descuento']}</td>
                    <td>${row['total']}</td>
                </tr>`;

                total += Math.floor(row['total']);
            });

            document.getElementById("tblGanancia").innerHTML = html;
            document.getElementById("totalGanancia").value = total;
        }

    })
    .catch(function (err) {
        console.log(err);
    });

}


