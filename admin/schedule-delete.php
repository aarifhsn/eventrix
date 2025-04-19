<?php include 'layouts/top.php'; ?>

<?php
$statement = $pdo->prepare("SELECT * FROM schedules WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if(!$total) {
    header('location: '.ADMIN_URL.'schedule.php');
    exit;
}

$result = $statement->fetchAll();
unlink('../uploads/'.$result[0]['photo']);

$q = $pdo->prepare("DELETE FROM schedules WHERE id=?");
$q->execute([$_REQUEST['id']]);
$_SESSION['success_message'] = "Data delete is successful";
header("location: ".ADMIN_URL."schedule.php");
exit;