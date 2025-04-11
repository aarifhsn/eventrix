<?php include("../config/config.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <link rel="icon" type="image/png" href="uploads/favicon.png">

    <title>Admin Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/font_awesome_5_free.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/duotone-dark.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/fontawesome-iconpicker.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/bootstrap4-toggle.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/style.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/components.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/air-datepicker.min.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/spacing.css">
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/dist/css/custom.css">

    <script src="<?php echo ADMIN_URL; ?>/dist/js/jquery-3.7.0.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/popper.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/tooltip.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/jquery.nicescroll.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/moment.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/stisla.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/jscolor.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/bootstrap-tagsinput.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/select2.full.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/iziToast.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/fontawesome-iconpicker.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/air-datepicker.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/tinymce/tinymce.min.js"></script>
    <script src="<?php echo ADMIN_URL; ?>/dist/js/bootstrap4-toggle.min.js"></script>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">

            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right justify-content-end rightsidetop">
                    <li class="nav-link">
                        <a href="" target="_blank" class="btn btn-warning">Front End</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img alt="image" src="uploads/user.jpg" class="rounded-circle-custom">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.html"><i class="far fa-user"></i> Edit
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="login.html"><i class="fas fa-sign-out-alt"></i>
                                    Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>



            <div class="main-sidebar">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html">Admin Panel</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html"></a>
                    </div>

                    <ul class="sidebar-menu">

                        <li class="active"><a class="nav-link" href="index.html"><i class="fas fa-hand-point-right"></i>
                                <span>Dashboard</span></a></li>

                        <li class="nav-item dropdown active">
                            <a href="#" class="nav-link has-dropdown"><i
                                    class="fas fa-hand-point-right"></i><span>Dropdown Items</span></a>
                            <ul class="dropdown-menu">
                                <li class="active"><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item
                                        1</a></li>
                                <li class=""><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 2</a>
                                </li>
                            </ul>
                        </li>

                        <li class=""><a class="nav-link" href="setting.html"><i class="fas fa-hand-point-right"></i>
                                <span>Setting</span></a></li>

                        <li class=""><a class="nav-link" href="form.html"><i class="fas fa-hand-point-right"></i>
                                <span>Form</span></a></li>

                        <li class=""><a class="nav-link" href="table.html"><i class="fas fa-hand-point-right"></i>
                                <span>Table</span></a></li>

                        <li class=""><a class="nav-link" href="invoice.html"><i class="fas fa-hand-point-right"></i>
                                <span>Invoice</span></a></li>

                    </ul>
                </aside>
            </div>