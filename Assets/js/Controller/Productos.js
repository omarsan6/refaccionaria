let tblProductos;

document.addEventListener("DOMContentLoaded", function () {
    tblProductos = $('#tblProductos').DataTable({
        ajax: {
            url: base_url + "Productos/listar",
            dataSrc: ''
        },
        responsive: true,
        columns: [
            {
                data: 'idinventario',
            },
            {
                data: 'foto',
            },
            {
                data: 'nombre',
            },
            {
                data: 'precioSalida'
            },
            {
                data: 'descripcion'
            },
            {
                data: 'existencia'
            },
            {
                data: 'fabricante'
            },
            {
                data: 'categoria'
            },
            {
                data: 'estado'
            },
            {
                data: 'acciones'
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    })
})

function frmProducto() {
    document.getElementById("my-modal-title").textContent = "Agregar Producto";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmProducto").reset();
    document.getElementById("idinventario").value = '';
    $("#nuevo_producto").modal("show");
    deleteImg();
}

function registrarProducto(e) {
    e.preventDefault();

    let nombre = document.getElementById("nombre");
    let precioEntrada = document.getElementById("precioEntrada");
    let precioSalida = document.getElementById("precioSalida");
    let descripcion = document.getElementById("descripcion");
    let fabricante = document.getElementById("fabricante");
    let categoria = document.getElementById("categoria");

    if (nombre.value == "" || precioEntrada.value == ""
        || precioSalida.value == "" || descripcion.value == "" || fabricante.value == ""
        || categoria.value == "") {

        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3500
        })

    } else {

        const frm = document.getElementById("frmProducto");

        const url = base_url + "Productos/registrar";

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

            let textoSinComilla = texto.replace(/['"]+/g, '');

            textoSinComilla = textoSinComilla.trim();

            console.log(textoSinComilla);

            if (textoSinComilla === "si") {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto registrado correctamente',
                    showConfirmButton: false,
                    timer: 3500
                })
                tblProductos.ajax.reload();
                frm.reset();
                $("#nuevo_producto").modal("hide");

            } else if (textoSinComilla == "modificado") {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto modificado correctamente',
                    showConfirmButton: false,
                    timer: 3500
                })
                tblProductos.ajax.reload();
                $("#nuevo_Producto").modal("hide");
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

function btnEditarProducto(idinventario) {

    document.getElementById("my-modal-title").textContent = "Editar Producto";
    document.getElementById("btnAccion").textContent = "Modificar";

    const url = base_url + "Productos/editar/" + idinventario;

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

            document.getElementById("idinventario").value = texto.idinventario;

            document.getElementById("nombre").value = texto.nombre;
            document.getElementById("precioEntrada").value = texto.precioEntrada;
            document.getElementById("precioSalida").value = texto.precioSalida;
            document.getElementById("descripcion").value = texto.descripcion;
            document.getElementById("fabricante").value = texto.fabricante;
            document.getElementById("categoria").value = texto.categoria_idcategoria;
            document.getElementById("img-preview").src = base_url + "Assets/inventario/"+texto.foto;
            document.getElementById("icon-cerrar").innerHTML = 
            `<button class="btn btn-danger" onclick="deleteImg();">
            <i class="fas fa-times"></i></button>`;

            document.getElementById("fotoActual").value = texto.foto;

            document.getElementById("icon-image").classList.add("d-none");


        })
        .catch(function (err) {
            console.log(err);
        });

    $("#nuevo_producto").modal("show");
}

function btnEliminarProducto(idinventario) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de eliminar?',
        text: "El Producto no se eliminará permanentemente, solo cambiará a estado 'Inactivo'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Productos/eliminar/" + idinventario;

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
                    // //convertir un string a objeto
                    let texto = JSON.parse(texto1)

                    

                    if (texto =="ok"){
                        swalWithBootstrapButtons.fire(
                            'Mensaje',
                            'Producto eliminado con éxito',
                            'success'
                        )
                        tblProductos.ajax.reload();
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
                'El Producto sigue activo',
                'error'
            )
        }
    })
}

function btnActivarProducto(idinventario) {

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

            const url = base_url + "Productos/activar/" + idinventario;

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
                            'Producto activado con éxito',
                            'success'
                        )
                        tblProductos.ajax.reload();
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
                'El Producto sigue inactivo',
                'error'
            )
        }
    })
}

function preview(e){
    const url = e.target.files[0];
    const urltmp = URL.createObjectURL(url);
    document.getElementById("img-preview").src = urltmp;
    document.getElementById("icon-image").classList.add("d-none");
    document.getElementById("icon-cerrar").innerHTML = `<button class="btn btn-danger" onclick="deleteImg();"><i class="fas fa-times"></i></button>
    ${url['name']}`;
}

function deleteImg(){
    document.getElementById("icon-cerrar").innerHTML = '';
    document.getElementById("icon-image").classList.remove("d-none");
    document.getElementById("img-preview").src = "";
    document.getElementById("foto").value = '';

    document.getElementById("fotoActual").value = '';


}