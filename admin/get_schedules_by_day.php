<?php
session_start();
include(__DIR__ . '/../config/config.php');

if (isset($_POST['schedule_day_id'])) {
    $dayId = $_POST['schedule_day_id'];

    $stmt = $pdo->prepare("SELECT * FROM schedules WHERE schedule_day_id = ?");
    $stmt->execute([$dayId]);
    $schedules = $stmt->fetchAll();

    echo '<option value="">Select Schedule</option>';
    foreach ($schedules as $schedule) {
        echo "<option value='{$schedule['id']}'>{$schedule['title']}</option>";
    }
}
