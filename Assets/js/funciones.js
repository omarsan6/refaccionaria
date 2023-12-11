let tblUsuarios;

let tblClientes;

document.addEventListener("DOMContentLoaded", function () {
    tblUsuarios = $('#tblUsuarios').DataTable({
        ajax: {
            url: base_url + "Usuarios/listar",
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
                data: 'rfc',
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
                data: 'caja'
            },
            {
                data: 'estado'
            },
            {
                data: 'acciones'
            }
        ]
    })

    //FIN USUARIOS

    tblClientes = $('#tblClientes').DataTable({
        ajax: {
            url: base_url + "Clientes/listar",
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
                data: 'correo',
            },
            {
                data: 'telefono'
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

function frmLogin(e) {
    e.preventDefault();

    let usuario = document.getElementById("usuario");
    let password = document.getElementById("password");

    if (usuario.value == "") {
        password.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
    } else if (password.value == "") {
        usuario.classList.remove("is-invalid");
        password.classList.add("is-invalid");
    } else {

        const url = base_url + "Usuarios/validar";

        //pasar las variables a traves de URLSearch
        const data = new URLSearchParams("usuario=" + usuario.value + "&password=" + password.value);

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

                if (textoSinComilla === "ok") {
                    window.location = base_url + "Administracion/home";
                } else {

                    document.getElementById("alert").classList.remove("d-none");
                    document.getElementById("alert").textContent = textoSinComilla;
                }

            })
            .catch(function (err) {
                console.log(err);
            });

    }
}

function frmUsuario() {
    document.getElementById("claves").classList.remove("d-none");
    document.getElementById("my-modal-title").textContent = "Agregar Usuario";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmUsuario").reset();
    $("#nuevo_usuario").modal("show");
}

function registrarUser(e) {
    e.preventDefault();

    let rfc = document.getElementById("rfc");
    let nombre = document.getElementById("nombre");
    let usuario = document.getElementById("usuario");
    let password = document.getElementById("password");
    let confirmar = document.getElementById("confirmar");
    let telefono = document.getElementById("telefono");
    let correo = document.getElementById("correo");
    let caja = document.getElementById("caja");

    let id_usuario = document.getElementById("id_usuario");

    if (rfc.value == "" || nombre.value == "" || usuario.value == ""
        || telefono.value == "" || correo.value == "" || caja.value == "") {

        alertas('Todos los campos son obligatorios','warning');

    } else {

        const frm = document.getElementById("frmUsuario");

        const url = base_url + "Usuarios/registrar";

        //pasar las variables a traves de URLSearch
        const data = new URLSearchParams("rfc=" + rfc.value + "&nombre=" + nombre.value
            + "&usuario=" + usuario.value + "&password=" + password.value + "&confirmar=" + confirmar.value
            + "&telefono=" + telefono.value + "&correo=" + correo.value + "&caja=" + caja.value + "&id_usuario=" + id_usuario.value);

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

                //let textoSinComilla = texto.replace(/['"]+/g, '');

                textoSinComilla = texto.trim();

                const res = JSON.parse(textoSinComilla);

                $("#nuevo_usuario").modal("hide");

                alertas(res.msg,res.icono);

                tblUsuarios.ajax.reload();

            })
            .catch(function (err) {
                console.log(err);
            });

    }
}

function btnEditarUsuario(id_usuario) {

    document.getElementById("my-modal-title").textContent = "Editar Usuario";
    document.getElementById("btnAccion").textContent = "Modificar";

    const url = base_url + "Usuarios/editar/" + id_usuario;

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

            console.log(texto.RFC);

            document.getElementById("id_usuario").value = texto.id_usuario;

            document.getElementById("rfc").value = texto.RFC;
            document.getElementById("nombre").value = texto.nombre;
            document.getElementById("usuario").value = texto.usuario;
            document.getElementById("telefono").value = texto.telefono;
            document.getElementById("correo").value = texto.correo;
            document.getElementById("caja").value = texto.id_caja;

            document.getElementById("claves").classList.add("d-none");

        })
        .catch(function (err) {
            console.log(err);
        });

    $("#nuevo_usuario").modal("show");
}

function btnEliminarUsuario(id_usuario) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de eliminar?',
        text: "El usuario no se eliminará permanentemente, solo cambiará a estado 'Inactivo'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Usuarios/eliminar/" + id_usuario;

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

                    alertas(texto.msg,texto.icono);

                    tblUsuarios.ajax.reload();

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
                'El usuario sigue activo',
                'error'
            )
        }
    })
}

function btnActivarUsuario(id_usuario) {

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

            const url = base_url + "Usuarios/activar/" + id_usuario;

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

                    alertas(texto.msg,texto.icono);

                    tblUsuarios.ajax.reload();

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
                'El usuario sigue inactivo',
                'error'
            )
        }
    })
}

function registrarPermisos(e){
    e.preventDefault();

    const url = base_url + "Usuarios/registrarPermisos";
    const frm = document.getElementById("formulario");
    const form = new FormData(frm);
            
    fetch(url, {
        method: 'POST',
        body:form
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

        if(res != ''){
            alertas(res.msg,res.icono);
        } else {
            alertas('Error no identificado','error');
        }
    })
    .catch(function (err) {
        console.log(err);
    });
}

//FIN USUARIO

function frmClientes() {
    document.getElementById("my-modal-title").textContent = "Agregar Cliente";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmClientes").reset();
    document.getElementById("id_cliente").value = '';
    $("#nuevo_cliente").modal("show");
}

function registrarCliente(e) {
    e.preventDefault();

    let rfc = document.getElementById("rfc");
    let nombre = document.getElementById("nombre");
    let correo = document.getElementById("correo");
    let telefono = document.getElementById("telefono");
    let direccion = document.getElementById("direccion");

    let id_cliente = document.getElementById("id_cliente");

    if (rfc.value == "" || nombre.value == "" ||  correo.value == "" || telefono.value == "" || direccion.value == "") {

        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3500
        })

    } else {

        const frm = document.getElementById("frmCliente");

        const url = base_url + "Clientes/registrar";

        //pasar las variables a traves de URLSearch
        const data = new URLSearchParams("rfc=" + rfc.value + "&nombre=" + nombre.value
            + "&telefono=" + telefono.value + "&correo=" + correo.value + "&direccion=" + direccion.value + "&id_cliente=" + id_cliente.value);

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
                        title: 'Cliente registrado correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })

                    frm.reset();
                    $("#nuevo_cliente").modal("hide");
                    tblClientes.ajax.reload();

                } else if (textoSinComilla == "modificado") {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Cliente modificado correctamente',
                        showConfirmButton: false,
                        timer: 3500
                    })
                    $("#nuevo_cliente").modal("hide");
                    tblClientes.ajax.reload();
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

function btnEditarCliente(id_cliente) {

    document.getElementById("my-modal-title").textContent = "Editar Cliente";
    document.getElementById("btnAccion").textContent = "Modificar";

    const url = base_url + "Clientes/editar/" + id_cliente;

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

            document.getElementById("id_cliente").value = texto.id_cliente;
            document.getElementById("rfc").value = texto.RFC;
            document.getElementById("nombre").value = texto.nombre;
            document.getElementById("correo").value = texto.correo;
            document.getElementById("telefono").value = texto.telefono;
            document.getElementById("direccion").value = texto.direccion;
        })
        .catch(function (err) {
            console.log(err);
        });

    $("#nuevo_cliente").modal("show");
}

function btnEliminarCliente(id_cliente) {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mr-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: '¿Está seguro de eliminar?',
        text: "El cliente no se eliminará permanentemente, solo cambiará a estado 'Inactivo'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',

    }).then((result) => {
        if (result.isConfirmed) {

            const url = base_url + "Clientes/eliminar/" + id_cliente;

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
                            'Cliente eliminado con éxito',
                            'success'
                        )
                        tblClientes.ajax.reload();
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
                'El Cliente sigue activo',
                'error'
            )
        }
    })
}

function btnActivarCliente(id_cliente) {

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

            const url = base_url + "Clientes/activar/" + id_cliente;

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
                            'Cliente activado con éxito',
                            'success'
                        )
                        tblClientes.ajax.reload();
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

//ALERTAS
function alertas(mensaje,icono){
    Swal.fire({
        position: 'center',
        icon: icono,
        title: mensaje,
        showConfirmButton: false,
        timer: 3500
    })
}

if(document.getElementById('stockMinimo')){
    reporteStock();
    productosMasVendidos();
}

function reporteStock(){

    const url = base_url + "Administracion/reporteStock";

    fetch(url, {
        method: 'GET'
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

        let nombre = [];
        let cantidad = [];

        for (let i = 0; i < res.length; i++) {
            
            nombre.push(res[i]['nombre']);
            cantidad.push(res[i]['existencia']);
            
        }

        var ctx = document.getElementById("stockMinimo");

        var myPieChart = new Chart(ctx,{
            type: 'pie',
            data: {
                labels: nombre,
                datasets: [{
                    data: cantidad,
                    backgroundColor: ['#007bff','#dc3545','#ffc107','#28a745'],
                }]
            }
        })
           

    })
    .catch(function (err) {
            console.log(err);
    });
}


function productosMasVendidos(){

    const url = base_url + "Administracion/productosMasvendidos";

    fetch(url, {
        method: 'GET'
    })
    .then(function (response) {
        if (response.ok) {
            return response.text();
        } else {
            throw "Error en la llamada Ajax";
        }
    })
    .then(function (texto) {

        // console.log(texto);

        const res = JSON.parse(texto);

        let nombre = [];
        let cantidad = [];

        for (let i = 0; i < res.length; i++) {
            nombre.push(res[i]['nombre']);
            cantidad.push(res[i]['total']);
        }

        var ctx = document.getElementById("productosMasVendidos");

        var myPieChart = new Chart(ctx,{
            type: 'doughnut',
            data: {
                labels: nombre,
            datasets: [{
                data: cantidad,
                backgroundColor: ['#007bff','#dc3545','#ffc107','#28a745'],
            }]
        }
})
           

    })
    .catch(function (err) {
            console.log(err);
    });
}

