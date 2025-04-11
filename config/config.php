<?php

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

define("BASE_URL", "http://localhost/eventrix");
define("ADMIN_URL", BASE_URL . "/admin");

// define("SMTP_HOST", "sandbox.smtp.mailtrap.io");
// define("SMTP_PORT", "587");
// define("SMTP_USERNAME", "8015a4c734102a");
// define("SMTP_PASSWORD", "a59f38709f1cc5");
// define("SMTP_ENCRYPTION", "tls");
// define("SMTP_FROM", "contact@yourwebsite.com");