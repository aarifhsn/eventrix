<?php
// Get the current file name (without .php)
$pageName = basename($_SERVER['PHP_SELF'], ".php");

// Replace dashes/underscores and capitalize
$pageTitle = ucwords(str_replace(['-', '_'], ' ', $pageName));
?>

<div class="common-banner" style="background-image: url(<?php echo BASE_URL; ?>/dist/images/banner.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="item">
                    <h2><?php echo $pageTitle; ?></h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                            <li class="breadcrumb-item active"><?php echo $pageTitle; ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>