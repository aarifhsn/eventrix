<?php
ob_start();
include(__DIR__ . '/../includes/header.php');

// Validate the incoming ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: ' . BASE_URL . 'organizers');
  exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM organizers WHERE id = ?");
$stmt->execute([$id]);
$organizer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$organizer) {
  header('Location: ' . BASE_URL . 'organizers');
  exit;
}

?>

<div id="speakers" class="pt_70 pb_70 white team speakers-item">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="speaker-detail-img">
          <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $organizer['photo']; ?>" />
        </div>
      </div>
      <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="speaker-detail">
          <h2><?php echo htmlspecialchars($organizer['name']); ?></h2>
          <h4 class="mb_20"><?php echo htmlspecialchars($organizer['designation']); ?></h4>
          <p>
            <?php echo nl2br(htmlspecialchars($organizer['bio'])); ?>
          </p>

          <h4>More Information</h4>
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th><b>Address:</b></th>
                <td><?php echo htmlspecialchars($organizer['address']); ?></td>
              </tr>
              <tr>
                <th><b>Email:</b></th>
                <td><?php echo htmlspecialchars($organizer['email']); ?></td>
              </tr>
              <tr>
                <th><b>Phone:</b></th>
                <td><?php echo htmlspecialchars($organizer['phone']); ?></td>
              </tr>
              <tr>
                <th><b>Social:</b></th>
                <td>
                  <ul class="social-icon">
                    <li>
                      <a href="<?php echo htmlspecialchars($organizer['facebook']); ?>"><i
                          class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a href="<?php echo htmlspecialchars($organizer['twitter']); ?>"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                      <a href="<?php echo htmlspecialchars($organizer['linkedin']); ?>"><i
                          class="fa fa-linkedin"></i></a>
                    </li>
                    <li>
                      <a href="<?php echo htmlspecialchars($organizer['instagram']); ?>"><i
                          class="fa fa-instagram"></i></a>
                    </li>
                  </ul>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
<?php ob_end_flush(); ?>