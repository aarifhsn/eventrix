<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Get all sponsors for this category
$stmt = $pdo->prepare("SELECT 
  s.*,
  sc.title AS category_title,
  sc.description AS category_description
  FROM sponsors s
  INNER JOIN sponsor_categories sc ON s.sponsor_category_id = sc.id
  WHERE s.id = ?");
$stmt->execute([$_GET['id']]);
$sponsors = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<div id="speakers" class="pt_70 pb_70 white team speakers-item">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="speaker-detail-img">
          <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $sponsors['logo']; ?>" />
        </div>
      </div>
      <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="speaker-detail">
          <h2><?php echo htmlspecialchars($sponsors['name']); ?></h2>
          <h4 class="mb_20"><?php echo htmlspecialchars($sponsors['title']); ?></h4>
          <p>
            <?php echo htmlspecialchars($sponsors['description']); ?>
          </p>

          <h4>More Information</h4>
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th><b>Address:</b></th>
                <td><?php echo htmlspecialchars($sponsors['address']); ?></td>
              </tr>
              <tr>
                <th><b>Email:</b></th>
                <td><?php echo htmlspecialchars($sponsors['email']); ?></td>
              </tr>
              <tr>
                <th><b>Phone:</b></th>
                <td><?php echo htmlspecialchars($sponsors['phone']); ?></td>
              </tr>
              <tr>
                <th><b>Website:</b></th>
                <td>
                  <a href="<?php echo htmlspecialchars($sponsors['website']); ?>"
                    target="_blank"><?php echo htmlspecialchars($sponsors['website']); ?></a>
                </td>
              </tr>
              <tr>
                <th><b>Social:</b></th>
                <td>
                  <ul class="social-icon">
                    <li>
                      <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-instagram"></i></a>
                    </li>
                  </ul>
                </td>
              </tr>
              <?php
              if (!empty($sponsors['map'])) { ?>
                <tr>
                  <th>Map:</th>
                  <td>
                    <iframe src="<?php echo htmlspecialchars($sponsors['map']); ?>" width="600" height="450"
                      style="border: 0" allowfullscreen="" loading="lazy"
                      referrerpolicy="no-referrer-when-downgrade"></iframe>
                  </td>
                </tr>
              <?php }
              ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>