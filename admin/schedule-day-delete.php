<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

$statement = $pdo->prepare("SELECT * FROM schedule_days WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if (!$total) {
    header('location: ' . ADMIN_URL . 'schedule-day.php');
    exit;
}

$statement = $pdo->prepare("DELETE FROM schedule_days WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$_SESSION['success_message'] = "Data delete is successful";
header("location: " . ADMIN_URL . "schedule-day.php");
exit;

