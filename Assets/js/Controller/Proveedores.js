let tblProveedores;

document.addEventListener("DOMContentLoaded", function () {
    tblProveedores = $('#tblProveedores').DataTable({
        ajax: {
            url: base_url + "Proveedores/listar",
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
                data: 'RFC',
            },
            {
                data: 'nombre',
            },
            {
                data: 'telefono',
            },
            {
                data: 'correo'
            },
            {
                data: 'direccion'
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

function frmProveedores() {
    document.getElementById("my-modal-title").textContent = "Agregar Proveedor";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmProveedores").reset();
    document.getElementById("id_proveedor").value = '';
    $("#nuevo_proveedor").modal("show");
}

function registrarProveedor(e) {
    e.preventDefault();

    let rfc = document.getElementById("rfc");
    let nombre = document.getElementById("nombre");
    let correo = document.getElementById("correo");
    let telefono = document.getElementById("telefono");
    let direccion = document.getElementById("direccion");

    let id_proveedor = document.getElementById("id_proveedor");

    if (rfc.value == "" || nombre.value == "" ||  correo.value == "" || telefono.value == "" || direccion.value == "") {

        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3500
        })

    } else {

        const frm = document.getElementById("frmProveedores");

        const url = base_url + "Proveedores/registrar";

        //pasar las variables a traves de URLSearch
        const data = new URLSearchParams("rfc=" + rfc.value + "&nombre=" + nombre.value
            + "&telefono=" + telefono.value + "&correo=" + correo.value + "&direccion=" + direccion.value + "&id_proveedor=" + id_proveedor.value);

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
                        title: 'Proveedor registrado correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })

                    frm.reset();
                    $("#nuevo_proveedor").modal("hide");
                    tblProveedores.ajax.reload();

                } else if (textoSinComilla == "modificado") {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Proveedor modificado correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })
                    $("#nuevo_proveedor").modal("hide");
                    tblProveedores.ajax.reload();
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

function btnEditarProveedor(id_proveedor) {
    document.getElementById("my-modal-title").textContent = "Editar proveedor";
    document.getElementById("btnAccion").textContent = "Modificar";

    const url = base_url + "Proveedores/editar/" + id_proveedor;

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
            let texto = JSON.parse(texto1);

            document.getElementById("id_proveedor").value = texto.id_proveedor;
            document.getElementById("rfc").value = texto.RFC;
            document.getElementById("nombre").value = texto.nombre;
            document.getElementById("correo").value = texto.correo;
            document.getElementById("telefono").value = texto.telefono;
            document.getElementById("direccion").value = texto.direccion;
        })
        .catch(function (err) {
            console.log(err);
        });

    $("#nuevo_proveedor").modal("show");
}

function btnEliminarProveedor(id_proveedor) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de eliminar?',
        text: "El proveedor no se eliminará permanentemente, solo cambiará a estado 'Inactivo'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Proveedores/eliminar/" + id_proveedor;

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
                            'Proveedor eliminado con éxito',
                            'success'
                        )
                        tblProveedores.ajax.reload();
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
                'El proveedor sigue activo',
                'error'
            )
        }
    })
}

function btnActivarProveedor(id_proveedor) {

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

            const url = base_url + "Proveedores/activar/" + id_proveedor;

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
                            'Proveedor activado con éxito',
                            'success'
                        )
                        tblProveedores.ajax.reload();
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
                'El proveedor sigue inactivo',
                'error'
            )
        }
    })
}