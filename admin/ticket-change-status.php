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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$statement = $pdo->prepare("SELECT * FROM tickets WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if (!$total) {
    header('location: ' . ADMIN_URL . '/ticket.php');
    exit;
}
foreach ($result as $row) {
    $payment_status = $row['payment_status'];
    $user_id = $row['user_id'];
    $orderNo = $row['orderNo'];
    $payment_method = $row['payment_method'];
    $per_ticket_price = $row['per_ticket_price'];
    $total_tickets = $row['total_tickets'];
    $total_price = $row['total_price'];
    $purchase_date_time = $row['purchase_date_time'];
}
if ($payment_status == 'Completed') {
    $new_status = 'Pending';
} else {
    $new_status = 'Completed';
}

$statement = $pdo->prepare("UPDATE tickets SET payment_status=? WHERE id=?");
$statement->execute([$new_status, $_REQUEST['id']]);

$statement = $pdo->prepare("SELECT * FROM users WHERE id=?");
$statement->execute([$user_id]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $email = $row['email'];
    $name = $row['name'];
}
$email_message = 'Dear ' . $name . ',<br><br>';
$email_message .= "Your payment is " . strtolower($new_status) . " by the admin.<br>";
$email_message .= '<br><b><u>Payment Details:</u></b><br>';
$email_message .= 'Order No: ' . $orderNo . '<br>';
$email_message .= 'Payment Method: ' . $payment_method . '<br>';
$email_message .= 'Per Ticket Price: ' . $per_ticket_price . '<br>';
$email_message .= 'Total Tickets: ' . $total_tickets . '<br>';
$email_message .= 'Total Price: ' . $total_price . '<br>';
$email_message .= 'Payment Method: ' . $payment_method . '<br>';
$email_message .= 'Payment Status: ' . $new_status . '<br>';
$email_message .= 'Date & Time: ' . $purchase_date_time . '<br>';
$email_message .= '<br>Thank you.';
$email_message .= '<br><br>';
$email_message .= 'Best Regards<br>';
$email_message .= 'Admin';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port = SMTP_PORT;
    $mail->setFrom(SMTP_FROM);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Payment is ' . $new_status;
    $mail->Body = $email_message;
    $mail->send();
    $success_message = 'Please check your email and follow the steps.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

$_SESSION['success_message'] = "Status is changed successfully";
header("location: " . ADMIN_URL . "/ticket.php");
exit;