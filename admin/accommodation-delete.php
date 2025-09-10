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
        throw new Exception("Invalid or missing accommodation ID.");
    }

    $id = (int) $_POST['id'];

    // Check if the accommodation day exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM accommodations WHERE id = ?");
    $stmt->execute([$id]);

    if ((int) $stmt->fetchColumn() === 0) {
        throw new Exception("Accommodation not found.");
    }

    // Delete the record
    $stmt = $pdo->prepare("DELETE FROM accommodations WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success_message'] = "Accommodation deleted successfully.";
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
}

// Redirect to listing
header("Location: " . ADMIN_URL . "/accommodation.php");
exit;
