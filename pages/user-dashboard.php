<?php

session_start();

ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

if (!isset($_SESSION['user'])) {
  header('Location: ' . BASE_URL . 'login');
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$statement = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);


?>

<div class="user-section pt_70 pb_70">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php include(__DIR__ . '/../templates/user-sidebar.php'); ?>
      </div>
      <div class="col-lg-9">
        <div class="d-flex">
          <h4 class="mb_15 fw600 w-50">User Detail:</h4>
          <p class="text-lg-end w-50"><a href="<?php echo BASE_URL; ?>user-profile">Edit Profile</a></p>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <th>Name:</th>
              <td><?php echo htmlspecialchars($user['name']); ?></td>
            </tr>
            <tr>
              <th>Email:</th>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
              <th>Phone:</th>
              <td><?php echo htmlspecialchars($user['phone'] ?? ''); ?></td>
            </tr>
            <tr>
              <th>Address:</th>
              <td><?php echo htmlspecialchars($user['address'] ?? ''); ?></td>
            </tr>
            <tr>
              <th>State:</th>
              <td><?php echo htmlspecialchars($user['state'] ?? ''); ?></td>
            </tr>
            <tr>
              <th>City:</th>
              <td><?php echo htmlspecialchars($user['city'] ?? ''); ?></td>
            </tr>
            <tr>
              <th>Country:</th>
              <td><?php echo htmlspecialchars($user['country'] ?? ''); ?></td>
            </tr>
            <tr>
              <th>Zip Code:</th>
              <td><?php echo htmlspecialchars($user['zip_code'] ?? ''); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>

<?php ob_end_flush(); ?>