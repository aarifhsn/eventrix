<?php
ob_start();
session_start();
include(__DIR__ . '/../config/config.php');

// Clear any existing session data related to the payment
unset($_SESSION['package_id']);
unset($_SESSION['package_name']);
unset($_SESSION['billing_name']);
unset($_SESSION['billing_email']);
unset($_SESSION['billing_phone']);
unset($_SESSION['billing_address']);
unset($_SESSION['billing_country']);
unset($_SESSION['billing_state']);
unset($_SESSION['billing_city']);
unset($_SESSION['billing_zip']);
unset($_SESSION['billing_note']);
unset($_SESSION['per_ticket_price']);
unset($_SESSION['total_tickets']);
unset($_SESSION['total_price']);

// Set cancellation message
$error_message = "Payment was cancelled. You can try again or choose a different payment method.";
$_SESSION['error_message'] = $error_message;

// Redirect back to home page or packages page
header('Location: ' . BASE_URL);
exit();

ob_end_flush();
?>