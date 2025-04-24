<?php

$stmt = $pdo->prepare("SELECT * FROM sponsors ORDER BY id ASC");
$stmt->execute();
$sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="sponsor-section" class="pt_70 pb_70 gray">
    <div class="container">
        <div class="row">
            <div class="col-sm-1 col-lg-2"></div>
            <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
                <h2 class="title-1 mb_15">
                    <span class="color_green">Our Sponsers</span>
                </h2>
                <p class="heading-space">
                    If you want to become a sponsor, please contact us. We offer different sponsorship packages that
                    will help
                    you promote your brand and reach a wider audience.
                </p>
            </div>
            <div class="col-sm-1 col-lg-2"></div>
        </div>
        <div class="row pt_40">
            <div class="col-md-12">
                <div class="owl-carousel">
                    <?php foreach ($sponsors as $sponsor): ?>
                        <div class="sponsors-logo">
                            <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $sponsor['logo']; ?>"
                                class="img-responsive" alt="sponsor logo">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>