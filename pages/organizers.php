<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

include(__DIR__ . '/../config/helpers.php');

// Fetch organizer Data
$organizersData = fetchAll($pdo, 'organizers');

?>

<div id="speakers" class="pt_50 pb_50 gray team speakers-item">
  <div class="container">
    <div class="row pt_40">
      <?php foreach ($organizersData as $organizer): ?>
        <div class="col-lg-3 col-sm-6 col-xs-12">
          <div class="team-img mb_20">
            <a href="<?php echo BASE_URL; ?>organizer?id=<?php echo $organizer['id']; ?>"><img
                src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $organizer['photo']; ?>" /></a>
          </div>
          <div class="team-info text-center">
            <h6><a
                href="<?php echo BASE_URL; ?>organizer?id=<?php echo $organizer['id']; ?>"><?php echo $organizer['name']; ?></a>
            </h6>
            <p><?php echo $organizer['designation']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>