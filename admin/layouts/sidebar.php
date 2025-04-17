<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php"></a>
        </div>

        <ul class="sidebar-menu">
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><a class="nav-link"
                    href="<?php echo ADMIN_URL; ?>dashboard.php"><i class="fas fa-hand-point-right"></i>
                    <span>Dashboard</span<?php echo ADMIN_URL; ?>dashboard.php>
                </a></li>

            <li
                class="nav-item dropdown <?php echo ($current_page == 'home-about-settings.php' || $current_page == 'home-banner-settings.php' || $current_page == 'home-counter-settings.php') ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i>
                    <span>Home Section</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo ($current_page == 'home-banner-settings.php') ? 'active' : ''; ?>"><a
                            class="nav-link" href="<?php echo ADMIN_URL; ?>home-banner-settings.php"><i
                                class="fas fa-hand-point-right"></i>
                            <span>Banner</span<?php echo ADMIN_URL; ?>home-banner-settings.php>
                        </a></li>
                    <li class="<?php echo ($current_page == 'home-about-settings.php') ? 'active' : ''; ?>"><a
                            class="nav-link" href="<?php echo ADMIN_URL; ?>home-about-settings.php"><i
                                class="fas fa-hand-point-right"></i>
                            <span>About</span<?php echo ADMIN_URL; ?>home-about-settings.php>
                        </a></li>
                    <li class="<?php echo ($current_page == 'home-counter-settings.php') ? 'active' : ''; ?>"><a
                            class="nav-link" href="<?php echo ADMIN_URL; ?>home-counter-settings.php"><i
                                class="fas fa-hand-point-right"></i>
                            <span>Counter</span<?php echo ADMIN_URL; ?>home-counter-settings.php>
                        </a></li>
                </ul>
            </li>
            <li class="<?php echo ($current_page == 'speakers-settings.php') ? 'active' : ''; ?>"><a class="nav-link"
                    href="<?php echo ADMIN_URL; ?>speakers-settings.php"><i class="fas fa-hand-point-right"></i>
                    <span>Speakers Section</span<?php echo ADMIN_URL; ?>speakers-settings.php>
                </a></li>

            <li class="<?php echo ($current_page == 'setting.php') ? 'active' : ''; ?>"><a class="nav-link"
                    href="<?php echo ADMIN_URL; ?>setting.php"><i class="fas fa-hand-point-right"></i>
                    <span>Setting</span></a></li>

            <li class="<?php echo ($current_page == 'form.php') ? 'active' : ''; ?>"><a class="nav-link"
                    href="<?php echo ADMIN_URL; ?>form.php"><i class="fas fa-hand-point-right"></i>
                    <span>Form</span></a></li>

            <li class="<?php echo ($current_page == 'table.php') ? 'active' : ''; ?>"><a class="nav-link"
                    href="<?php echo ADMIN_URL; ?>table.php"><i class="fas fa-hand-point-right"></i>
                    <span>Table</span></a></li>

            <li class="<?php echo ($current_page == 'invoice.php') ? 'active' : ''; ?>"><a class="nav-link"
                    href="<?php echo ADMIN_URL; ?>invoice.php"><i class="fas fa-hand-point-right"></i>
                    <span>Invoice</span></a></li>

        </ul>
    </aside>
</div>