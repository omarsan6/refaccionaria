function modificarEmpresa(){
    const frm = document.getElementById("frmEmpresa");
    const form = new FormData(frm);

    const url = base_url + "Administracion/modificar/";

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
                .then(function (textoData) {
                    //quitar espacios vacios 
                    let texto1 = textoData.trim();
                    //convertir un string a objeto
                    let texto = JSON.parse(texto1)

                    if(texto == "ok"){
                        alert("Modificado");
                    }
                })
                .catch(function (err) {
                    console.log(err);
                });
}