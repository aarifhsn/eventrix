<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Fetch sponsor categories
$sponsorCategories = fetchAll($pdo, 'sponsor_categories');

?>

<div id="sponsorsectionList" class="pt_50 pb_50 white">
  <div class="container">
    <?php
    foreach ($sponsorCategories as $category):
      // Fetch sponsors for this category
      $stmt = $pdo->prepare("SELECT * FROM sponsors WHERE sponsor_category_id=? ORDER BY id ASC");
      $stmt->execute([$category['id']]);
      $sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (empty($sponsors)) {
        continue;
      }
      ?>
      <div class="row">
        <div class="col-sm-1 col-lg-2"></div>
        <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
          <h2 class="title-1 mb_10">
            <span class="color_red"><?php echo $category['title']; ?></span>
          </h2>
          <p class="heading-space">
            <?php echo $category['description']; ?>
          </p>
        </div>
        <div class="col-sm-1 col-lg-2"></div>
      </div>

      <div class="row pt_40 mb_50">

        <?php
        foreach ($sponsors as $sponsor): ?>
          <div class="col-md-3">
            <div class="sponsors-logo">
              <a href="sponsor?id=<?php echo $sponsor['id']; ?>"><img
                  src="<?php echo ADMIN_URL; ?>uploads/<?php echo $sponsor['featured_photo']; ?>" class="img-responsive"
                  alt="" /></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>