<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Dhaka'); // or whatever your timezone is

$dbhost = 'localhost';
$dbname = 'eventrix';
$dbuser = 'root';
$dbpass = '';
try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error :" . $exception->getMessage();
}

define("SITE_NAME", "Eventrix");
define("BASE_URL", "http://localhost/eventrix/");
define("ADMIN_URL", BASE_URL . "admin/");

define("SMTP_HOST", "sandbox.smtp.mailtrap.io");
define("SMTP_PORT", "587");
define("SMTP_USERNAME", "12e2ad175f6c4f");
define("SMTP_PASSWORD", "028651bd4821a5");
define("SMTP_ENCRYPTION", "tls");
define("SMTP_FROM", "contact@arifhassan.com");

