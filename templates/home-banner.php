<?php
// get from home_banners table
try {
    $stmt = $pdo->prepare("SELECT * FROM home_banners LIMIT 1");
    $stmt->execute();
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no banner found, create default values
    if (!$banner) {
        $banner = [
            'subheading' => 'Welcome',
            'heading' => 'Event Title',
            'description' => 'Event description will appear here',
            'event_date' => date('Y-m-d', strtotime('+30 days')),
            'background' => 'banner-home.jpg'
        ];
    }
} catch (Exception $e) {
    // Handle database errors
    $error_message = $e->getMessage();
}

// Get background image URL
$background_image = !empty($banner['background']) ?
    ADMIN_URL . "uploads/" . $banner['background'] :
    ADMIN_URL . "/dist/images/banner-home.jpg";
?>

<div class="container-fluid home-banner"
    style="background-image:url(<?php echo htmlspecialchars($background_image); ?>)">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="static-banner-detail">
                    <h4><?php echo htmlspecialchars($banner['subheading'] ?? ''); ?></h4>
                    <h2><?php echo htmlspecialchars($banner['heading'] ?? ''); ?></h2>
                    <p>
                        <?php echo htmlspecialchars($banner['description'] ?? ''); ?>
                    </p>
                    <?php
                    // Calculate countdown only if we have a valid event date
                    if (!empty($banner['event_date'])) {
                        $current_time = strtotime(date('Y-m-d H:i:s'));
                        $event_time = strtotime($banner['event_date']);
                        $diff = max(0, $event_time - $current_time); // Ensure diff is never negative
                        $days = floor($diff / (60 * 60 * 24));
                        $hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
                        $minutes = floor(($diff % (60 * 60)) / (60));
                        $seconds = $diff % 60;
                    } else {
                        $days = $hours = $minutes = $seconds = 0;
                    }
                    ?>
                    <div class="counter-area">
                        <div class="countDown clearfix">
                            <div class="row count-down-bg">
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="single-count day">
                                        <h1 class="days"><?php echo $days; ?></h1>
                                        <p class="days_ref">days</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="single-count hour">
                                        <h1 class="hours"><?php echo $hours; ?></h1>
                                        <p class="hours_ref">hours</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="single-count min">
                                        <h1 class="minutes"><?php echo $minutes; ?></h1>
                                        <p class="minutes_ref">minutes</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="single-count second">
                                        <h1 class="seconds"><?php echo $seconds; ?></h1>
                                        <p class="seconds_ref">seconds</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="buy.html" class="banner_btn video_btn">BUY TICKETS</a>
                </div>
            </div>
        </div>
    </div>
</div>