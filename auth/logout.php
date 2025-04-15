<?php

// Start output buffering
ob_start();

// Include the header
include(__DIR__ . '/../includes/header.php');

// Clear all session variables
$_SESSION = array();

// If a session cookie is used, destroy it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Set success message in a new session
session_start();
$_SESSION['success_message'] = "Logout successful!";

// Redirect to login page
header("Location: " . BASE_URL . "login");

// Flush the output buffer
ob_end_flush();
exit();

?>