<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

$testimonials = fetchAll($pdo, 'testimonials');

?>

<div id="gallery-section" class="pt_50 pb_70 gray testimonialSec1">
  <div class="container">
    <div class="row pt_40">
      <div class="col-md-12">
        <div id="testimonial-slider" class="owl-carousel">
          <?php foreach ($testimonials as $testimonial): ?>
            <div class="testimonial">
              <div class="testimonial-content">
                <div class="testimonial-icon">
                  <i class="fa fa-quote-left"></i>
                </div>
                <p class="description">
                  <?php echo $testimonial['comment']; ?>
                </p>
              </div>
              <div class="photo">
                <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $testimonial['photo']; ?>" alt="" />
              </div>
              <h3 class="title"><?php echo $testimonial['name']; ?></h3>
              <span class="post"><?php echo $testimonial['designation']; ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>