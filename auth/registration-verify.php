<?php
session_start();

ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Check if email and token are provided
if (!isset($_REQUEST['email']) || !isset($_REQUEST['token']) || empty($_REQUEST['email']) || empty($_REQUEST['token'])) {
    $_SESSION['error_message'] = "Invalid verification link. Please check your email for the correct link.";
    header('location: ' . BASE_URL);
    exit;
}

// Sanitize inputs
$email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
$token = preg_replace('/[^a-zA-Z0-9]/', '', $_REQUEST['token']);

// Check if email is valid format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Invalid email format in verification link.";
    header('location: ' . BASE_URL);
    exit;
}

try {
    // Check if the email and token combination exists and user is not already verified
    $statement = $pdo->prepare("SELECT * FROM users WHERE email=? AND token=? AND status=0");
    $statement->execute([$email, $token]);
    $total = $statement->rowCount();

    if ($total) {
        // Begin transaction for updating user status
        $pdo->beginTransaction();

        try {
            // Update user to verified status
            $statement = $pdo->prepare("UPDATE users SET token=?, status=? WHERE email=? AND token=?");
            $statement->execute(['', 1, $email, $token]);

            $pdo->commit();

            $_SESSION['success_message'] = "Email verification successful! You can now log in to your account.";
            header('location: ' . BASE_URL . 'login');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Verification update error: " . $e->getMessage());
            $_SESSION['error_message'] = "An error occurred during verification. Please try again or contact support.";
            header('location: ' . BASE_URL);
            exit;
        }
    } else {
        // Check if user is already verified
        $statement = $pdo->prepare("SELECT * FROM users WHERE email=? AND status=1");
        $statement->execute([$email]);
        if ($statement->rowCount() > 0) {
            $_SESSION['info_message'] = "Your email is already verified. You can log in to your account.";
            header('location: ' . BASE_URL . 'login');
            exit;
        } else {
            $_SESSION['error_message'] = "Invalid or expired verification link. Please request a new verification email.";
            header('location: ' . BASE_URL . 'resend-verification'); // Create this page for users to request new verification email
            exit;
        }
    }
} catch (Exception $e) {
    error_log("Verification error: " . $e->getMessage());
    $_SESSION['error_message'] = "An error occurred during verification. Please try again or contact support.";
    header('location: ' . BASE_URL);
    exit;
}

?>