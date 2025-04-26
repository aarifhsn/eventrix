<?php
$current_page = basename($_SERVER['PHP_SELF']);

function isActive($pages)
{
    global $current_page;
    return in_array($current_page, (array) $pages) ? 'active' : '';
}

function isDropdownShow($pages)
{
    global $current_page;
    return in_array($current_page, (array) $pages) ? 'show' : '';
}
?>


<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?php echo ADMIN_URL; ?>dashboard.php">Admin Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo ADMIN_URL; ?>dashboard.php">AP</a>
        </div>

        <ul class="sidebar-menu">
            <li class="<?php echo isActive('dashboard.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>dashboard.php">
                    <i class="fas fa-hand-point-right"></i> <span>Dashboard</span>
                </a>
            </li>

            <?php $homePages = ['home-banner-settings.php', 'home-about-settings.php', 'home-counter-settings.php']; ?>
            <li class="nav-item dropdown <?php echo isActive($homePages); ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i> <span>Home
                        Section</span></a>
                <ul class="dropdown-menu <?php echo isDropdownShow($homePages); ?>">
                    <li class="<?php echo isActive('home-banner-settings.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>home-banner-settings.php">
                            <i class="fas fa-hand-point-right"></i> <span>Banner</span>
                        </a>
                    </li>
                    <li class="<?php echo isActive('home-about-settings.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>home-about-settings.php">
                            <i class="fas fa-hand-point-right"></i> <span>About</span>
                        </a>
                    </li>
                    <li class="<?php echo isActive('home-counter-settings.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>home-counter-settings.php">
                            <i class="fas fa-hand-point-right"></i> <span>Counter</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php $speakerPages = ['speaker.php', 'schedule-day.php', 'schedule.php', 'speakers-schedule.php']; ?>
            <li class="nav-item dropdown <?php echo isActive($speakerPages); ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i> <span>Speaker
                        Section</span></a>
                <ul class="dropdown-menu <?php echo isDropdownShow($speakerPages); ?>">
                    <li class="<?php echo isActive('speaker.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>speaker.php">
                            <i class="fas fa-hand-point-right"></i> <span>Speaker</span>
                        </a>
                    </li>
                    <li class="<?php echo isActive('schedule-day.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>schedule-day.php">
                            <i class="fas fa-hand-point-right"></i> <span>Schedule Day</span>
                        </a>
                    </li>
                    <li class="<?php echo isActive('schedule.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>schedule.php">
                            <i class="fas fa-hand-point-right"></i> <span>Schedules</span>
                        </a>
                    </li>
                    <li class="<?php echo isActive('speakers-schedule.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>speakers-schedule.php">
                            <i class="fas fa-hand-point-right"></i> <span>Speakers Schedule</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php $sponsorPages = ['sponsor-category.php', 'sponsor.php']; ?>
            <li class="nav-item dropdown <?php echo isActive($sponsorPages); ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i> <span>Sponsor
                        Section</span></a>
                <ul class="dropdown-menu <?php echo isDropdownShow($sponsorPages); ?>">
                    <li class="<?php echo isActive('sponsor-category.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>sponsor-category.php">
                            <i class="fas fa-hand-point-right"></i> <span>Sponsor Category</span>
                        </a>
                    </li>
                    <li class="<?php echo isActive('sponsor.php'); ?>">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>sponsor.php">
                            <i class="fas fa-hand-point-right"></i> <span>Sponsor</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="<?php echo isActive('organizer.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>organizer.php">
                    <i class="fas fa-hand-point-right"></i> <span>Organizer Section</span>
                </a>
            </li>
            <li class="<?php echo isActive('accommodation.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>accommodation.php">
                    <i class="fas fa-hand-point-right"></i> <span>Accommodation Section</span>
                </a>
            </li>
            <li class="<?php echo isActive('faq.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>faq.php">
                    <i class="fas fa-hand-point-right"></i> <span>FAQ Section</span>
                </a>
            </li>
            <li class="<?php echo isActive('testimonial.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>testimonial.php">
                    <i class="fas fa-hand-point-right"></i> <span>Testimonial Section</span>
                </a>
            </li>
            <li class="<?php echo isActive('blog.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>blog.php">
                    <i class="fas fa-hand-point-right"></i> <span>Blog Section</span>
                </a>
            </li>
            <li class="<?php echo isActive('package.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>package.php">
                    <i class="fas fa-hand-point-right"></i> <span>Package Section</span>
                </a>
            </li>
            <li class="<?php echo isActive('feature.php'); ?>">
                <a class="nav-link" href="<?php echo ADMIN_URL; ?>feature.php">
                    <i class="fas fa-hand-point-right"></i> <span>Feature Section</span>
                </a>
            </li>

        </ul>
    </aside>
</div>