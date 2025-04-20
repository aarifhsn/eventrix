<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');
include(__DIR__ . '/../config/helpers.php');

// Fetch Data
$scheduleDaysData = fetchAll($pdo, 'schedule_days', 'date ASC');

// Fetch Data
$scheduleData = fetchAll($pdo, 'schedules', 'item_order ASC');
?>

<div id="schedule-section" class="gray pt_50 pb_50">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 schedule-tab">
        <ul id="scheduleTab" class="nav nav-tabs justify-content-center text-center row-gap-2">
          <?php
          $i = 0;
          foreach ($scheduleDaysData as $scheduleDay):
            $i++;
            ?>
            <li class="nav-item">
              <a href="#itemid<?php echo $i; ?>" data-toggle="tab"
                class="nav-link <?php echo ($i == 1) ? 'active' : ''; ?>">
                <p><?php echo $scheduleDay['title']; ?></p>
                <span><?php echo $scheduleDay['date']; ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <div id="scheduleTabContent" class="tab-content">
          <?php
          $i = 0;
          foreach ($scheduleDaysData as $scheduleDay):

            $i++;
            ?>
            <div id="itemid<?php echo $i; ?>" class="tab-pane <?php echo ($i == 1) ? 'active show' : ''; ?> fade">
              <?php foreach ($scheduleData as $schedule):
                if ($schedule['schedule_day_id'] != $scheduleDay['id'])
                  continue;
                ?>
                <div class="row speaker-mainbox">
                  <div class="col-lg-4 col-xs-12">
                    <div class="speaker-img">
                      <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $schedule['photo']; ?>" />
                    </div>
                  </div>
                  <div class="col-lg-8 col-xs-12">
                    <div class="speaker-box">
                      <h2><?php echo $schedule['name']; ?></h2>
                      <h3><?php echo $schedule['title']; ?></h3>
                      <p>
                        <?php echo nl2br($schedule['description']); ?>
                      </p>
                      <h3>Speakers:</h3>
                      <h4>
                        <a href="speaker.php" class="badge badge-primary">John Smith</a>
                        <a href="speaker.php" class="badge badge-primary">Pat Flynn</a>
                      </h4>
                      <h3>Location:</h3>
                      <h4>
                        <span><?php echo $schedule['location']; ?></span>
                      </h4>
                      <h3>Time:</h3>
                      <h4>
                        <span><?php echo $schedule['time']; ?></span>
                      </h4>
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