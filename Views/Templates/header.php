<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Panel de administración</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url; ?>Assets/css/sb-admin-2.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url; ?>Assets/DataTables/datatables.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url; ?>Assets/DataTables/Bootstrap-4-4.6.0/css/bootstrap.min.css.map" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url; ?>Assets/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url; ?>Assets/css/estilos.css" rel="stylesheet" type="text/css">


    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="<?php echo base_url; ?>Assets/js/all.min.js"></script>

    
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url ?>Administracion/home">
                <div class="sidebar-brand-text mx-3">Refacciones <sup>Shalom</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span class="mr-5">Administración</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-light bg-gradient py-2 collapse-inner rounded">
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Usuarios">Usuarios</a>
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Administracion">Configuración</a>
                    </div>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCaja"
                    aria-expanded="true" aria-controls="collapseCaja">
                    <i class="fas fa-box"></i>
                    <span class="mr-5">Cajas</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                <div id="collapseCaja" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-light bg-gradient py-2 collapse-inner rounded">
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Cajas">Cajas</a>
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Cajas/arqueo">Arqueo de caja</a>
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Cajas/gananciaDia">Ganancia por día</a>
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Cajas/gananciaMes">Ganancia semanal</a>
                    </div>
                </div>

                <a class="nav-link" href="<?php echo base_url;?>Clientes">
                    <i class="fas fa-user mr-2"></i>
                    <span class="mr-5">Clientes</span>
                </a>

                <a class="nav-link" href="<?php echo base_url;?>Categorias">
                    <i class="fas fa-list-alt mr-2"></i>
                    <span class="mr-5">Categorias</span>
                </a>

                <a class="nav-link" href="<?php echo base_url;?>Proveedores">
                    <i class="fas fa-users mr-2"></i>
                    <span class="mr-5">Proveedores</span>
                </a>

                <a class="nav-link" href="<?php echo base_url;?>Productos">
                    <i class="fab fa-product-hunt mr-2"></i>
                    <span class="mr-5">Productos</span>
                </a>
               
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompras"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa-brands fa-shopify"></i>
                    <span class="mr-5">Entradas</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                <div id="collapseCompras" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-light bg-gradient py-2 collapse-inner rounded">
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Compras">Nueva Compra</a>
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Compras/historial">Historial compras</a>
                    </div>
                </div>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa-brands fa-shopify"></i>
                    <span class="mr-5">Salidas</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                <div id="collapseVentas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-light bg-gradient py-2 collapse-inner rounded">
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Compras/ventas">Nueva venta</a>
                        <a class="collapse-item p-3" href="<?php echo base_url ?>Compras/historialVenta">Historial ventas</a>
                    </div>
                </div>


            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle text-center" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-bars"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo base_url; ?>Usuarios/salir">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    

                   

                
            
            