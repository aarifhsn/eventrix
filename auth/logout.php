<?php
ob_start();
session_start();

include(__DIR__ . '/../includes/header.php');
unset($_SESSION['user']);
$_SESSION['success_message'] = "Logout is successful!";

ob_end_flush();

header('location: ' . BASE_URL . 'login');
exit;
