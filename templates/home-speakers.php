<?php
// Fetch speakers data
$stmt = $pdo->prepare("SELECT * FROM speakers ORDER BY id ASC");
$stmt->execute();
$speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="speakers" class="pt_70 pb_70 gray team">
    <div class="container">
        <div class="row">
            <div class="col-sm-1 col-lg-2"></div>
            <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
                <h2 class="title-1 mb_10">
                    <span class="color_green">Speakers</span>
                </h2>
                <p class="heading-space">
                    You will find below the list of our valuable speakers. They are all experts in their field and will
                    share
                    their knowledge with you.
                </p>
            </div>
            <div class="col-sm-1 col-lg-2"></div>
        </div>
        <div class="row pt_40">
            <?php foreach ($speakers as $speaker): ?>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="team-img mb_20">
                        <a href="<?php echo BASE_URL; ?>speaker?id=<?php echo $speaker['id']; ?>">
                            <?php if ($speaker['photo']): ?>
                                <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $speaker['photo']; ?>" />
                            <?php else: ?>
                                <img src="<?php echo ADMIN_URL; ?>uploads/default.png" />
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="team-info text-center">
                        <h6><a
                                href="<?php echo BASE_URL; ?>speaker?id=<?php echo $speaker['id']; ?>"><?php echo $speaker['name']; ?></a>
                        </h6>
                        <p><?php echo $speaker['designation']; ?></p>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>