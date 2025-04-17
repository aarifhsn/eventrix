<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

$stmt = $pdo->prepare("SELECT * FROM speakers ORDER BY id ASC");
$stmt->execute();
$speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div id="speakers" class="pt_70 pb_70 white team speakers-item">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="speaker-detail-img">
          <img src="images/speaker-1.jpg" />
        </div>
      </div>
      <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="speaker-detail">
          <h2><?php echo $speakers[0]['name']; ?></h2>
          <h4 class="mb_20"><?php echo $speakers[0]['designation']; ?></h4>
          <p>
            <?php echo $speakers[0]['bio']; ?>
          </p>

          <h4>More Information</h4>
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th><b>Address:</b></th>
                <td><?php echo $speakers[0]['address']; ?></td>
              </tr>
              <tr>
                <th><b>Email:</b></th>
                <td><?php echo $speakers[0]['email']; ?></td>
              </tr>
              <tr>
                <th><b>Phone:</b></th>
                <td><?php echo $speakers[0]['phone']; ?></td>
              </tr>
              <tr>
                <th><b>Website:</b></th>
                <td>
                  <a href="<?php echo $speakers[0]['website']; ?>"
                    target="_blank"><?php echo $speakers[0]['website']; ?></a>
                </td>
              </tr>
              <tr>
                <th><b>Social:</b></th>
                <td>
                  <ul class="social-icon">
                    <li>
                      <a href="<?php echo $speakers[0]['facebook']; ?>"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a href="<?php echo $speakers[0]['twitter']; ?>"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                      <a href="<?php echo $speakers[0]['linkedin']; ?>"><i class="fa fa-linkedin"></i></a>
                    </li>
                    <li>
                      <a href="https://<?php echo $speakers[0]['instagram']; ?>"><i class="fa fa-instagram"></i></a>
                    </li>
                  </ul>
                </td>
              </tr>
            </table>
          </div>

          <h4>My Sessions</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="speaker-img">
                <img src="<?php echo BASE_URL; ?>dist/images/day1_session1.jpg" />
              </div>
              <div class="speaker-box">
                <h3>Introduction to PHP and Laravel</h3>
                <h4>
                  <span>Tim Center, 34, Park Street, NYC, USA</span><br />
                  <span>Sep 20, 2024 (Day 1)</span><br />
                  <span>09:00 AM - 09:45 AM</span>
                </h4>
              </div>
            </div>
            <div class="col-md-6">
              <div class="speaker-img">
                <img src="<?php echo BASE_URL; ?>dist/images/day3_session1.jpg" />
              </div>
              <div class="speaker-box">
                <h3>User Experience (UX) Design Principles</h3>
                <h4>
                  <span>Tim Center, 34, Park Street, NYC, USA</span><br />
                  <span>Sep 22, 2024 (Day 3)</span><br />
                  <span>10:00 AM - 10:30 AM</span>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>