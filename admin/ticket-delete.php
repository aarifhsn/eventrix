<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Check for messages in session
initMessages();

$statement = $pdo->prepare("SELECT * FROM tickets WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if (!$total) {
    header('location: ' . ADMIN_URL . 'ticket.php');
    exit;
}

$q = $pdo->prepare("DELETE FROM tickets WHERE id=?");
$q->execute([$_REQUEST['id']]);

$_SESSION['success_message'] = "Data delete is successful";
header("location: " . ADMIN_URL . "ticket.php");
exit;