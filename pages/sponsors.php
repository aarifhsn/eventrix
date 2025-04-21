<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');


// Fetch full sponsors with sponsor_categories
$stmt = $pdo->prepare("
  SELECT 
    s.id AS sponsor_id,
    s.title AS sponsor_title,
    s.description,
    sc.id AS sponsor_category_id,
    sc.title AS sponsor_category_title,
    sc.description AS sponsor_category_description
  FROM sponsors s
  LEFT JOIN sponsor_categories sc ON sc.id = s.sponsor_category_id
  ORDER BY s.title ASC
");
$stmt->execute();
$sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="sponsorsectionList" class="pt_50 pb_50 white">
  <div class="container">
    <?php foreach ($sponsors as $sponsor): ?>
      <div class="row">
        <div class="col-sm-1 col-lg-2"></div>
        <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
          <h2 class="title-1 mb_10">
            <span class="color_red"><?php echo $sponsor['sponsor_category_title']; ?></span>
          </h2>
          <p class="heading-space">
            <?php echo $sponsor['sponsor_category_description']; ?>
          </p>
        </div>
        <div class="col-sm-1 col-lg-2"></div>
      </div>

      <div class="row pt_40 mb_50">
        <div class="col-md-3">
          <div class="sponsors-logo">
            <a href="sponsor.php"><img src="dist/images/partner-1.png" class="img-responsive" alt="" /></a>
          </div>
        </div>
        <div class="col-md-3">
          <div class="sponsors-logo">
            <a href="sponsor.php"><img src="dist/images/partner-2.png" class="img-responsive" alt="" /></a>
          </div>
        </div>
        <div class="col-md-3">
          <div class="sponsors-logo">
            <a href="sponsor.php"><img src="dist/images/partner-3.png" class="img-responsive" alt="" /></a>
          </div>
        </div>
        <div class="col-md-3">
          <div class="sponsors-logo">
            <a href="sponsor.php"><img src="dist/images/partner-4.png" class="img-responsive" alt="" />
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>