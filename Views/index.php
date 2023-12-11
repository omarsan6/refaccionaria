<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Refacciones Shalom - Inicio de sesión</title>

    <link rel="shortcut icon" href="<?php echo base_url; ?>Assets/img/logo.png" />

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url; ?>Assets/css/sb-admin-2.css" rel="stylesheet" type="text/css">

    <!-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> -->

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url; ?>Assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Font awesome -->
    <script src="<?php echo base_url; ?>Assets/js/all.min.js"></script>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="<?php echo base_url; ?>Assets/img/logo.png" alt="" srcset="">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5 mt-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido</h1>
                                    </div>
                                    <form id="frmLogin" class="user">
                                        <div class="form-group">
                                            <label for="usuario"> <i class="fas fa-user"></i> Usuario</label>
                                            <input type="text" class="form-control form-control-user"
                                                id="usuario" name="usuario" aria-describedby="emailHelp"
                                                placeholder="Nombre de usuario">
                                        </div>
                                        <div class="form-group">
                                            <label for="password"> <i class="fas fa-key"></i> Contraseña</label>
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Ingrese contraseña">
                                        </div>

                                        <div class="alert alert-danger text-center d-none" id="alert" role="alert">
                                            
                                        </div>

                                        <button class="btn btn-primary w-100" type="submit" onclick="frmLogin(event)">Entrar</button>
                                        <hr>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url; ?>Assets/js/jquery-3.6.3.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/datatables.min.js"></script>

    <script src="<?php echo base_url; ?>Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url; ?>Assets/js/sb-admin-2.min.js"></script>

    <script>
        const base_url = "<?php echo base_url;?>";
    </script>

    <script src="<?php echo base_url; ?>Assets/js/funciones.js"></script>
</body>

</html>