<?php
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');
include(__DIR__ . '/../config/helpers.php');

// Fetch schedule days
$scheduleDaysStmt = $pdo->prepare("SELECT id, title, date FROM schedule_days ORDER BY date ASC");
$scheduleDaysStmt->execute();
$scheduleDaysData = $scheduleDaysStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch full schedule with speakers
$stmt = $pdo->prepare("
    SELECT 
        s.id AS schedule_id,
        s.schedule_day_id,
        s.name AS schedule_name,
        s.title AS schedule_title,
        s.description,
        s.location,
        s.time,
        s.photo,
        sd.title AS day_title,
        sd.date AS day_date,
        sp.id AS speaker_id,
        sp.name AS speaker_name
    FROM schedules s
    LEFT JOIN schedule_days sd ON sd.id = s.schedule_day_id
    LEFT JOIN speaker_schedule ss ON ss.schedule_id = s.id
    LEFT JOIN speakers sp ON sp.id = ss.speaker_id
    ORDER BY sd.date ASC, s.time ASC
");
$stmt->execute();
$rawScheduleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by schedule_id to collect multiple speakers
$scheduleData = [];
foreach ($rawScheduleData as $row) {
  $id = $row['schedule_id'];
  if (!isset($scheduleData[$id])) {
    $scheduleData[$id] = [
      'schedule_id' => $id,
      'schedule_day_id' => $row['schedule_day_id'],
      'schedule_name' => $row['schedule_name'],
      'schedule_title' => $row['schedule_title'],
      'description' => $row['description'],
      'location' => $row['location'],
      'time' => $row['time'],
      'photo' => $row['photo'],
      'day_title' => $row['day_title'],
      'day_date' => $row['day_date'],
      'speakers' => [],
    ];
  }

  if ($row['speaker_id']) {
    $scheduleData[$id]['speakers'][] = [
      'id' => $row['speaker_id'],
      'name' => $row['speaker_name'],
    ];
  }
}
?>

<!-- HTML Display -->
<div id="schedule-section" class="gray pt_50 pb_50">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 schedule-tab">
        <!-- Nav tabs -->
        <ul id="scheduleTab" class="nav nav-tabs justify-content-center text-center row-gap-2">
          <?php foreach ($scheduleDaysData as $i => $day): ?>
            <li class="nav-item">
              <a href="#itemid<?php echo $day['id']; ?>" data-toggle="tab"
                class="nav-link <?php echo ($i == 0) ? 'active' : ''; ?>">
                <p><?php echo htmlspecialchars($day['title']); ?></p>
                <span><?php echo htmlspecialchars($day['date']); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <!-- Tab content -->
        <div id="scheduleTabContent" class="tab-content">
          <?php foreach ($scheduleDaysData as $i => $day): ?>
            <div id="itemid<?php echo $day['id']; ?>" class="tab-pane fade <?php echo ($i == 0) ? 'show active' : ''; ?>">
              <?php foreach ($scheduleData as $schedule): ?>
                <?php if ($schedule['schedule_day_id'] != $day['id'])
                  continue; ?>
                <div class="row speaker-mainbox mb-4">
                  <div class="col-lg-4 col-xs-12">
                    <div class="speaker-img">
                      <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo htmlspecialchars($schedule['photo']); ?>"
                        class="img-fluid" />
                    </div>
                  </div>
                  <div class="col-lg-8 col-xs-12">
                    <div class="speaker-box">
                      <h2><?php echo htmlspecialchars($schedule['schedule_name']); ?></h2>
                      <h3><?php echo htmlspecialchars($schedule['schedule_title']); ?></h3>
                      <p><?php echo nl2br(htmlspecialchars($schedule['description'])); ?></p>

                      <h3>Speakers:</h3>
                      <h4>
                        <?php foreach ($schedule['speakers'] as $speaker): ?>
                          <a href="speaker?id=<?php echo $speaker['id']; ?>" class="badge badge-primary">
                            <?php echo htmlspecialchars($speaker['name']); ?>
                          </a>
                        <?php endforeach; ?>
                      </h4>

                      <h3>Location:</h3>
                      <h4><?php echo htmlspecialchars($schedule['location']); ?></h4>

                      <h3>Time:</h3>
                      <h4><?php echo htmlspecialchars($schedule['time']); ?></h4>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>