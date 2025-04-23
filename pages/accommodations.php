<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

$stmt = $pdo->prepare("SELECT * FROM accommodations");
$stmt->execute();
$accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="speakers" class="pt_70 pb_70 white team speakers-item">
  <div class="container">

    <?php foreach ($accommodations as $accommodation): ?>
      <div class="row mb_40">
        <div class="col-lg-4 col-sm-12 col-xs-12">
          <div class="speaker-detail-img">
            <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $accommodation['photo']; ?>" />
          </div>
        </div>
        <div class="col-lg-8 col-sm-12 col-xs-12">
          <div class="speaker-detail">
            <h2 class="mb_15"><?php echo $accommodation['name']; ?></h2>
            <p>
              <?php echo $accommodation['description']; ?>
            </p>
            <div class="table-responsive">
              <table class="table table-bordered">
                <tr>
                  <th><b>Address:</b></th>
                  <td>
                    <?php echo $accommodation['address']; ?>
                  </td>
                </tr>
                <tr>
                  <th><b>Email:</b></th>
                  <td><?php echo $accommodation['email']; ?></td>
                </tr>
                <tr>
                  <th><b>Phone:</b></th>
                  <td><?php echo $accommodation['phone']; ?></td>
                </tr>
                <tr>
                  <th><b>Website:</b></th>
                  <td>
                    <a href="<?php echo $accommodation['website']; ?>"
                      target="_blank"><?php echo $accommodation['website']; ?></a>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>