<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right justify-content-end rightsidetop">
        <li class="nav-link">
            <a href="<?php echo BASE_URL; ?>" target="_blank" class="btn btn-warning">Front End</a>
        </li>
        <?php if (isset($_SESSION['admin'])) { ?>
            <li class="nav-item dropdown">
                <p class="d-sm-none d-lg-inline-block text-white">Hi, <?php echo $_SESSION['admin']['name']; ?></p>
            </li>
        <?php } ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <?php
                $photo = $_SESSION['admin']['photo'] ?? '';
                $photo_url = !empty($photo) ? ADMIN_URL . "/uploads/$photo" : ADMIN_URL . "/uploads/default.png";
                ?>
                <img src="<?php echo htmlspecialchars($photo_url); ?>" alt="Profile Photo"
                    class="rounded-circle-custom">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="right:0;">
                <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>/profile.php"><i class="far fa-user"></i>
                        Edit
                        Profile</a></li>
                <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>/logout.php"><i
                            class="fas fa-sign-out-alt"></i>
                        Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>