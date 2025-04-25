<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

try {
    // Ensure this is a feature request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    // Validate and sanitize the ID
    if (!isset($_POST['id']) || !filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
        throw new Exception("Invalid or missing feature ID.");
    }

    $id = (int) $_POST['id'];

    // Check if the feature day exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM features WHERE id = ?");
    $stmt->execute([$id]);

    if ((int) $stmt->fetchColumn() === 0) {
        throw new Exception("feature not found.");
    }

    // Delete the record
    $stmt = $pdo->prepare("DELETE FROM features WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success_message'] = "feature deleted successfully.";
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
}

// Redirect to listing
header("Location: " . ADMIN_URL . "feature.php");
exit;
