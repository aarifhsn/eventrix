<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

include(__DIR__ . '/../config/helpers.php');

// Fetch all packages
$packages = fetchAll($pdo, 'packages');

// Fetch all features
$allFeatures = fetchAll($pdo, 'features');

// Fetch selected feature IDs for this package
$statement = $pdo->prepare("SELECT feature_id FROM feature_package");
$statement->execute();
$selectedFeatures = $statement->fetchAll(PDO::FETCH_COLUMN);

?>

<div id="price-section" class="pt_50 pb_70 gray prices">
  <div class="container">
    <div class="row pt_40">
      <?php foreach ($packages as $package): ?>
        <div class="col-md-4 col-sm-12 my-4">
          <div class="info">
            <h5 class="event-ti-style"><?php echo $package['title']; ?></h5>
            <h3 class="event-ti-style"><?php echo $package['price']; ?></h3>
            <ul>
              <?php foreach ($allFeatures as $feature): ?>
                <?php if (in_array($feature['id'], $selectedFeatures)): ?>
                  <li><i class="fa fa-check"></i> <?php echo $feature['name']; ?></li>
                <?php else: ?>
                  <li><i class="fa fa-times"></i> <?php echo $feature['name']; ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
            <div class="global_btn mt_20">
              <a class="btn_two" href="buy.php">Buy Ticket</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>