<?php
// config.sample.php
// Copy this file to config.php and update with your real credentials.

date_default_timezone_set('UTC'); // Change if needed

// Database configuration
$dbhost = 'localhost';
$dbname = 'your_database_name';
$dbuser = 'your_database_user';
$dbpass = 'your_database_password';

try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}

// Site settings
define("SITE_NAME", "YourSiteName");
define("BASE_URL", "http://localhost/yoursite/");
define("ADMIN_URL", BASE_URL . "admin");

// SMTP configuration
define("SMTP_HOST", "smtp.example.com");
define("SMTP_PORT", "587");
define("SMTP_USERNAME", "your_smtp_username");
define("SMTP_PASSWORD", "your_smtp_password");
define("SMTP_ENCRYPTION", "tls");
define("SMTP_FROM", "no-reply@example.com");
