<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');
include(__DIR__ . '/../config/helpers.php');

// Fetch Data
$speakersData = fetchAll($pdo, 'speakers', 'id ASC');

?>

<div id="speakers" class="pt_50 pb_50 gray team speakers-item">
  <div class="container">
    <div class="row pt_40">
      <?php foreach ($speakersData as $speaker): ?>
        <div class="col-lg-3 col-sm-6 col-xs-12">
          <div class="team-img mb_20">
            <a href="speaker.php">
              <?php if ($speaker['photo']): ?>
                <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $speaker['photo']; ?>" />
              <?php else: ?>
                <img src="<?php echo ADMIN_URL; ?>uploads/default.png" />
              <?php endif; ?>
            </a>
          </div>
          <div class="team-info text-center">
            <h6><a href="speaker.php"><?php echo $speaker['name']; ?></a></h6>
            <p><?php echo $speaker['designation']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>