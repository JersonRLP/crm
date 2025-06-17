<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
<!-- ✅ META TAGS -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title>Dreamchat - Dashboard</title>

<!-- ✅ ICON -->
<link rel="shortcut icon" type="image/x-icon" href="/crm/public/assets/img/favicon.png">

<!-- ✅ BOOTSTRAP CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">

<!-- ✅ DATATABLES CSS (DataTables + Bootstrap 5 integration) -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">

<!-- ✅ TU ESTILO PERSONAL (DEBE IR DESPUÉS) -->
<link rel="stylesheet" href="/crm/public/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="/crm/public/assets/css/feathericon.min.css">
<link rel="stylesheet" href="/crm/public/assets/plugins/morris/morris.css">
<link rel="stylesheet" href="/crm/public/assets/css/style.css">

<!-- ✅ JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ✅ BOOTSTRAP BUNDLE -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
 -->
<!-- ✅ DATATABLES JS -->
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<!-- En tu HEAD, después de los estilos de DataTables -->
<style>

</style>



</head>

<body>

    <div class="main-wrapper">

        <div class="header">

            <div class="header-left">
                <a href="index.html" class="logo">
                    <img src="/crm/public/assets/img/logo.png" alt="Logo">
                </a>
                <a href="index.html" class="logo logo-small">
                    <img src="/crm/public/assets/img/logo-small.png" alt="Logo" width="30" height="30">
                </a>
            </div>

            <a href="javascript:void(0);" id="toggle_btn">
                <i class="fe fe-text-align-left"></i>
            </a>
            <div class="top-nav-search">
                <form>
                    <input type="text" class="form-control" placeholder="Search here">
                    <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <a class="mobile_btn" id="mobile_btn">
                <i class="fa fa-bars"></i>
            </a>


            <ul class="nav user-menu">

                <li class="nav-item dropdown noti-dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <i class="fa fa-bell"></i> <span class="badge badge-pill">3</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="/crm/public/assets/img/profiles/avatar-02.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Carlson Tech</span> has approved <span class="noti-title">your estimate</span></p>
                                                <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="/crm/public/assets/img/profiles/avatar-11.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">International Software Inc</span> has sent you a invoice in the amount of <span class="noti-title">$218</span></p>
                                                <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="/crm/public/assets/img/profiles/avatar-17.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">John Hendry</span> sent a cancellation request <span class="noti-title">Apple iPhone XR</span></p>
                                                <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="/crm/public/assets/img/profiles/avatar-13.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Mercury Software Inc</span> added a new product <span class="noti-title">Apple MacBook Pro</span></p>
                                                <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="#">View all Notifications</a>
                        </div>
                    </div>
                </li>


                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img"><img class="rounded-circle" src="/crm/public/assets/img/profiles/avatar-01.jpg" width="31" alt="Seema Sisty"></span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="/crm/public/assets/img/profiles/avatar-01.jpg" alt="User Image" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>Seema Sisty</h6>
                                <p class="text-muted mb-0">Administrator</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="general.html">My Profile</a>
                        <a class="dropdown-item" href="general.html">Account Settings</a>
                        <a class="dropdown-item" href="login.html">Logout</a>
                    </div>
                </li>

            </ul>

        </div>


        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">
                        </li>
                        <li class="active">
                            <a href="/crm/app/views/partials/principal.php" class="load-view"><i class="fe fe-home"></i> <span>Dashboard</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fe fe-users"></i> <span> Usuarios</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="/crm/app/views/partials/listaUser.php" class="load-view" data-modulo="cliente">Listado de Contactos</a></li>
                                <li><a href="#">Blocked User</a></li>
                                <li><a href="#">Report User</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="/crm/app/views/partials/vista1.php" class="load-view"><i class="fe fe-file"></i> <span>Vista 1</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="page-wrapper">
            <div class="content container-fluid">

                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Blank Page</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/crm/app/views/partials/principal.php" class="load-view">Dashboard</a></li>
                                <li class="breadcrumb-item active">Blank Page</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" id="content">
                        <?php include __DIR__ . "/partials/principal.php"; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

 <script src="/crm/public/assets/js/bootstrap.bundle.min.js"></script>

    <script src="/crm/public/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="/crm/public/assets/js/script.js"></script>
    <script src="/crm/public/js/menu.js"></script>
    <script src="/crm/public/js/cliente.js"></script>
</body>

</html>