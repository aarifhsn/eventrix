<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

try {
    // Ensure this is a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    // Validate and sanitize the ID
    if (!isset($_POST['id']) || !filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
        throw new Exception("Invalid or missing schedule day ID.");
    }

    $id = (int) $_POST['id'];

    // Check if the schedule day exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM schedule_days WHERE id = ?");
    $stmt->execute([$id]);

    if ((int) $stmt->fetchColumn() === 0) {
        throw new Exception("Schedule day not found.");
    }
    $stmt = $pdo->prepare("SELECT * FROM schedules WHERE schedule_day_id = ?");
    $stmt->execute([$_REQUEST['id']]);
    $result = $stmt->rowCount();
    if ($result > 0) {
        $_SESSION['error_message'] = "This Schedule day is already assigned to a schedule. Cannot delete.";
        header("Location: " . ADMIN_URL . "/schedule-day.php");
        exit;
    } else {
        // Delete the record
        $stmt = $pdo->prepare("DELETE FROM schedule_days WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success_message'] = "Schedule day deleted successfully.";
    }

} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
}

// Redirect to listing
header("Location: " . ADMIN_URL . "/schedule-day.php");
exit;
