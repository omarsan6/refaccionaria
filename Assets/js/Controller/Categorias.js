let tblCategorias;

document.addEventListener("DOMContentLoaded", function () {
    tblCategorias = $('#tblCategorias').DataTable({
        ajax: {
            url: base_url + "Categorias/listar",
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
                data: 'idcategoria',
            },
            {
                data: 'nombre',
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


function frmCategoria() {
    document.getElementById("my-modal-title").textContent = "Agregar Categoria";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmCategoria").reset();
    document.getElementById("idcategoria").value = '';
    $("#nuevo_categoria").modal("show");
}

function registrarCategoria(e) {
    e.preventDefault();

    let nombre = document.getElementById("nombre");

    let descripcion = document.getElementById("descripcion");

    let idcategoria = document.getElementById("idcategoria");

    if (nombre.value == "" || descripcion.value == "") {

        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3500
        })

    } else {

        const frm = document.getElementById("frmCategoria");

        const url = base_url + "Categorias/registrar";

        //pasar las variables a traves de URLSearch
        const data = new URLSearchParams("nombre=" + nombre.value + "&descripcion=" + descripcion.value
             + "&idcategoria=" + idcategoria.value);

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
                        title: 'Categoria registrado correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })

                    frm.reset();
                    $("#nuevo_categoria").modal("hide");
                    tblCategorias.ajax.reload();

                } else if (textoSinComilla == "modificado") {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Categoria modificada correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })
                    frm.reset();
                    $("#nuevo_categoria").modal("hide");
                    tblCategorias.ajax.reload();
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

function btnEditarCategoria(idcategoria) {

    document.getElementById("my-modal-title").textContent = "Editar Categoria";
    document.getElementById("btnAccion").textContent = "Modificar";

    const url = base_url + "Categorias/editar/" + idcategoria;

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

            document.getElementById("idcategoria").value = texto.idcategoria;
            document.getElementById("nombre").value = texto.nombre;
            document.getElementById("descripcion").value = texto.descripcion;
        })
        .catch(function (err) {
            console.log(err);
        });

    $("#nuevo_categoria").modal("show");
}

function btnEliminarCategoria(idcategoria) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de eliminar?',
        text: "La categoria no se eliminará permanentemente, solo cambiará a estado 'Inactivo'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Categorias/eliminar/" + idcategoria;

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
                            'Categoria eliminada con éxito',
                            'success'
                        )
                        tblCategorias.ajax.reload();
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
                'La categoria sigue activa',
                'error'
            )
        }
    })
}

function btnActivarCategoria(idcategoria) {

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

            const url = base_url + "Categorias/activar/" + idcategoria;

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
                            'Categoria activada con éxito',
                            'success'
                        )
                        tblCategorias.ajax.reload();
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
                'La categoria sigue inactivo',
                'error'
            )
        }
    })
}