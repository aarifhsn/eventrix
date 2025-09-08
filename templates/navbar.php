<div class="container main-menu" id="navbar">
    <div class="row">
        <div class="col-lg-2 col-sm-12 d-flex align-items-center">
            <div class=" fs-6 text-capitalize navbar-height" style="font-weight: 600; font-size: 24px;">
                <a href="<?php echo BASE_URL; ?>" id="logo1" class="text-decoration-none"><?php echo SITE_NAME; ?></a>
            </div>
        </div>
        <div class="col-lg-10 col-sm-12">
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul id="navContent" class="navbar-nav mr-auto navigation">
                        <li>
                            <a class="smooth-scroll nav-link" href="<?php echo BASE_URL; ?>">Home</a>
                        </li>
                        <li>
                            <a class="smooth-scroll nav-link" href="<?php echo BASE_URL; ?>speakers">Speakers</a>
                        </li>
                        <li>
                            <a class="smooth-scroll nav-link" href="<?php echo BASE_URL; ?>schedule">Schedule</a>
                        </li>
                        <li>
                            <a class="smooth-scroll nav-link" href="<?php echo BASE_URL; ?>pricing">Pricing</a>
                        </li>
                        <li>
                            <a class="smooth-scroll nav-link" href="<?php echo BASE_URL; ?>blog">Blog</a>
                        </li>
                        <li class="nav-item dropdown"> <a class="dropdown-toggle" href="#" id="navbarDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pages </a>
                            <div class="dropdown-menu" id="dropmenu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>sponsors">Sponsors</a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>organizers">Organizers</a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>accommodations">Accommodations</a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>photo-gallery">Photo
                                    Gallery</a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>video-gallery">Video
                                    Gallery</a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>faq">FAQ</a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>testimonials">Testimonials</a>
                            </div>
                        </li>
                        <li>
                            <a class="smooth-scroll nav-link" href="<?php echo BASE_URL; ?>contact">Contact</a>
                        </li>
                        <li class="member-login-button">
                            <div class="inner">
                                <?php
                                if (isset($_SESSION['user'])) {
                                    echo '<a href="' . BASE_URL . 'user-dashboard" class="smooth-scroll nav-link">Dashboard</a>';
                                } else {
                                    echo '<a class="smooth-scroll nav-link" href="' . BASE_URL . 'login">
                                    <i class="fa fa-sign-in"></i> Login
                                </a>';
                                }
                                ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>