<?php
// get from home_banners table
try {
    $stmt = $pdo->prepare("SELECT * FROM home_abouts LIMIT 1");
    $stmt->execute();
    $aboutData = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no banner found, create default values
    if (empty($aboutData['heading']) && empty($aboutData['description'])) {
        $aboutData = [
            'heading' => 'Welcome To Our Website',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. ',
            'button_text' => 'READ MORE',
            'button_url' => '#',
            'status' => 1
        ];
    }
} catch (Exception $e) {
    // Handle database errors
    $error_message = $e->getMessage();
}

// Get the photo
$photo = !empty($aboutData['photo']) ?
    ADMIN_URL . "/uploads/" . $aboutData['photo'] :
    ADMIN_URL . "/dist/img/about.jpg";
?>

<?php if (!empty($aboutData['status']) && $aboutData['status'] == 1): ?>

    <section id="about-section" class="pt_70 pb_70 white">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2><span class="color_green"><?php echo htmlspecialchars($aboutData['heading']); ?></span></h2>
                        </div>
                    </div>
                    <div class="about-details">
                        <p><?php echo htmlspecialchars($aboutData['description']); ?></p>
                        <div class="global_btn mt_20">
                            <a class="btn_one"
                                href="<?php echo htmlspecialchars($aboutData['button_url']); ?>"><?php echo htmlspecialchars($aboutData['button_text']); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-12 col-xs-12">
                    <div class="about-details-img">
                        <img src="<?php echo $photo; ?>" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>