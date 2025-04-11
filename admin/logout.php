<?php
session_start();

// Destroy the session or just unset the 'admin'
unset($_SESSION['admin']);
// session_destroy(); // Optional: clears everything

header('Location: login.php');
exit;
