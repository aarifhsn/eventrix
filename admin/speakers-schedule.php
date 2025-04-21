<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Check if admin is logged in
checkAdminAuth();
initMessages();

// Initialize variables
$assigned = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_schedule_to_speaker_form'])) {
    try {
        $speakerId = $_POST['speaker_id'];
        $scheduleId = $_POST['schedule_id'];
        $scheduleDayId = $_POST['schedule_day_id'];

        $stmt = $pdo->prepare("INSERT INTO speaker_schedule (speaker_id, schedule_day_id, schedule_id) VALUES (?, ?, ?)");
        $stmt->execute([$speakerId, $scheduleDayId, $scheduleId]);

        $success_message = "Schedule assigned to speaker successfully.";
    } catch (Exception $e) {
        $error_message = "Error assigning schedule to speaker: " . $e->getMessage();
    }
}

// Fetch all assigned schedules for display
try {
    $stmt = $pdo->prepare("SELECT
        ss.id as speaker_schedule_id,
        s.id as schedule_id,
        s.title as schedule_title,
        s.time as schedule_time,
        sd.id as schedule_day_id,
        sd.title as day_title,
        sp.id as speaker_id,
        sp.name as speaker_name
    FROM speaker_schedule ss
    JOIN schedules s ON s.id = ss.schedule_id
    JOIN schedule_days sd ON sd.id = ss.schedule_day_id
    JOIN speakers sp ON sp.id = ss.speaker_id");
    $stmt->execute();
    $assigned = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching assigned schedules: " . $e->getMessage();
    header("location: " . ADMIN_URL . "speakers-schedule.php");
    exit;
}

// Fetch all from schedule-days table
try {
    $stmt = $pdo->prepare("SELECT * FROM schedule_days");
    $stmt->execute();
    $scheduleDays = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching schedule days: " . $e->getMessage();
    header("location: " . ADMIN_URL . "speakers-schedule.php");
    exit;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Assign Schedule to Speaker</h1>
        </div>

        <div class="section-body">

            <!-- Assigned Schedules Table -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Assigned Schedules</h4>
                        </div>
                        <div class="card-body table-responsive">
                            <?php echo displaySuccess($success_message); ?>

                            <?php echo displayError($error_message); ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Speaker</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Schedule Item</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($assigned) > 0): ?>
                                        <?php foreach ($assigned as $index => $row): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($row['speaker_name']) ?></td>
                                                <td><?= htmlspecialchars($row['day_title']) ?></td>
                                                <td><?= htmlspecialchars($row['schedule_time']) ?></td>
                                                <td><?= htmlspecialchars($row['schedule_title']) ?></td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>schedule-day-edit.php?id="
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>

                                                    <form method="POST" action="<?= ADMIN_URL ?>speakers-schedule-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                                        <input type="hidden" name="id"
                                                            value="<?php echo htmlspecialchars($row['speaker_schedule_id']) ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">No schedules assigned yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="speaker">Speaker</label>
                                    <select name="speaker_id" id="speaker" class="form-control" required>
                                        <option value="">Select Speaker</option>
                                        <?php
                                        $speakers = $pdo->query("SELECT * FROM speakers")->fetchAll();
                                        foreach ($speakers as $speaker) {
                                            echo "<option value='{$speaker['id']}'>{$speaker['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="schedule_day">Schedule Day</label>
                                    <select name="schedule_day_id" id="schedule_day" class="form-control" required>
                                        <option value="">Select Day</option>
                                        <?php
                                        foreach ($scheduleDays as $day) {
                                            echo "<option value='{$day['id']}'>{$day['title']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="schedule">Schedule Item</label>
                                    <select name="schedule_id" id="schedule" class="form-control" required>
                                        <option value="">Select Schedule</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary"
                                    name="assign_schedule_to_speaker_form">Assign</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?php include("layouts/footer.php"); ?>


<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#schedule_day').on('change', function () {
        var dayId = $(this).val();
        $('#schedule').html('<option>Loading...</option>');

        $.ajax({
            url: 'get_schedules_by_day.php',
            method: 'POST',
            data: { schedule_day_id: dayId },
            success: function (response) {
                $('#schedule').html(response);
            }
        });
    });
</script>