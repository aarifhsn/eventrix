<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM schedule_days WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header('location: '.ADMIN_URL.'schedule-day.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM schedules WHERE schedule_day_id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if($total) {
    $_SESSION['error_message'] = "This schedule day has some schedules. So, it can not be deleted.";
    header('location: '.ADMIN_URL.'schedule-day.php');
    exit;
} else {
    $q = $pdo->prepare("DELETE FROM schedule_days WHERE id=?");
    $q->execute([$_REQUEST['id']]);
    $_SESSION['success_message'] = "Data delete is successful";
    header("location: ".ADMIN_URL."schedule-day.php");
    exit;    
}

