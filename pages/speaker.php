<?php

ob_start();
include(__DIR__ . '/../includes/header.php');

// Validate the incoming ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: ' . BASE_URL . 'speakers');
  exit;
}

$id = (int) $_GET['id']; // type cast to ensure safety

$stmt = $pdo->prepare("SELECT * FROM speakers WHERE id = ?");
$stmt->execute([$id]);
$speaker = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$speaker) {
  header('Location: ' . BASE_URL . 'speakers');
  exit;
}

// Get all schedules for this speaker
$stmt = $pdo->prepare("
    SELECT s.*,
    ss.speaker_id,
    sd.title AS day_title,
    sd.date AS day_date
    FROM schedules s
    INNER JOIN schedule_days sd ON s.schedule_day_id = sd.id
    INNER JOIN speaker_schedule ss ON s.id = ss.schedule_id
    WHERE ss.speaker_id = ?
    ORDER BY s.id ASC
");
$stmt->execute([$id]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="common-banner" style="background-image: url(<?php echo BASE_URL; ?>/dist/images/banner.jpg)">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="item">
          <h2><?php echo ucwords($speaker['name']); ?></h2>
          <div class="breadcrumb-container">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>speakers">Speakers</a></li>
              <li class=" breadcrumb-item active"><?php echo ucwords($speaker['name']); ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="speakers" class="pt_70 pb_70 white team speakers-item">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="speaker-detail-img">
          <?php if ($speaker['photo']): ?>
            <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $speaker['photo']; ?>" />
          <?php else: ?>
            <img src="<?php echo ADMIN_URL; ?>uploads/default.png" />
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="speaker-detail">
          <h2><?php echo htmlspecialchars($speaker['name']); ?></h2>
          <h4 class="mb_20"><?php echo htmlspecialchars($speaker['designation']); ?></h4>
          <p><?php echo nl2br(htmlspecialchars($speaker['bio'])); ?></p>

          <h4>More Information</h4>
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th><b>Address:</b></th>
                <td><?php echo htmlspecialchars($speaker['address']); ?></td>
              </tr>
              <tr>
                <th><b>Email:</b></th>
                <td><?php echo htmlspecialchars($speaker['email']); ?></td>
              </tr>
              <tr>
                <th><b>Phone:</b></th>
                <td><?php echo htmlspecialchars($speaker['phone']); ?></td>
              </tr>
              <tr>
                <th><b>Website:</b></th>
                <td>
                  <a href="<?php echo htmlspecialchars($speaker['website']); ?>" target="_blank">
                    <?php echo htmlspecialchars($speaker['website']); ?>
                  </a>
                </td>
              </tr>
              <tr>
                <th><b>Social:</b></th>
                <td>
                  <ul class="social-icon">
                    <li><a href="<?php echo htmlspecialchars($speaker['facebook']); ?>"><i
                          class="fa fa-facebook"></i></a></li>
                    <li><a href="<?php echo htmlspecialchars($speaker['twitter']); ?>"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="<?php echo htmlspecialchars($speaker['linkedin']); ?>"><i
                          class="fa fa-linkedin"></i></a></li>
                    <li><a href="https://<?php echo htmlspecialchars($speaker['instagram']); ?>"><i
                          class="fa fa-instagram"></i></a></li>
                  </ul>
                </td>
              </tr>
            </table>
          </div>

          <h4>My Sessions</h4>
          <div class="row">
            <?php foreach ($sessions as $session): ?>
              <div class="col-md-6">
                <div class="speaker-img">
                  <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $session['photo']; ?>" />
                </div>
                <div class="speaker-box">
                  <h3><?php echo $session['title']; ?></h3>
                  <h4>
                    <span><?php echo $session['location']; ?></span><br />
                    <?php echo date('F j, Y', strtotime($session['day_date'])); ?>
                    (<?php echo $session['name']; ?>)</span><br />
                    <span><?php echo $session['time']; ?></span>
                  </h4>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
<?php ob_end_flush(); ?>