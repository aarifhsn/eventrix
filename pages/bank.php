<?php

ob_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

include(__DIR__ . '/../config/helpers.php');
include(__DIR__ . '/../config/config-payment.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<?php
if (!isset($_SESSION['package_id'])) {
    header('location: ' . BASE_URL);
    exit;
}
?>

<?php
if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['bank_transaction_info'] == '') {
            throw new Exception("Transaction Information can not be empty");
        }

        $order_number = time() . rand(1000, 9999);
        $currency = 'USD';

        $payment_id = $order_number;

        // Insert into database
        $statement = $pdo->prepare("INSERT INTO tickets (
                                user_id,
                                package_id,
                                package_name,
                                billing_name,
                                billing_email,
                                billing_phone,
                                billing_address,
                                billing_country,
                                billing_state,
                                billing_city,
                                billing_zip,
                                billing_note,
                                payment_method,
                                payment_currency,
                                payment_status,
                                payment_id,
                                transaction_id,
                                bank_transaction_info,
                                per_ticket_price,
                                total_tickets,
                                total_price,
                                purchase_date_time
                            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([
            $_SESSION['user']['id'],
            $_SESSION['package_id'],
            $_SESSION['package_name'],
            $_SESSION['billing_name'],
            $_SESSION['billing_email'],
            $_SESSION['billing_phone'],
            $_SESSION['billing_address'],
            $_SESSION['billing_country'],
            $_SESSION['billing_state'],
            $_SESSION['billing_city'],
            $_SESSION['billing_zip'],
            $_SESSION['billing_note'],
            'Bank',
            $currency,
            'Pending',
            $payment_id,
            '',
            $_POST['bank_transaction_info'],
            $_SESSION['per_ticket_price'],
            $_SESSION['total_tickets'],
            $_SESSION['total_price'],
            date('Y-m-d H:i:s')
        ]);


        // Send Email to Admin
        $statement = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $admin_email = $row['email'];
        }

        $link = ADMIN_URL . '/ticket.php';
        $email_message = "Please click on the following link to see the pending payments: <br>";
        $email_message .= '<a href="' . $link . '">' . $link . '</a>';

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
            $mail->addAddress($admin_email);
            $mail->isHTML(true);
            $mail->Subject = 'A new payment for bank is pending';
            $mail->Body = $email_message;
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $success_message = "Payment information is submitted, but still it is pending until verified by admin. So please wait for admin approval.";
        $_SESSION['success_message'] = $success_message;

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

        header('location: ' . BASE_URL);
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . BASE_URL . "bank");
        exit;
    }
}
?>

<div class="common-banner"
    style="background-image:url(<?php echo BASE_URL; ?>uploads/<?php echo $setting_data[0]['banner']; ?>)">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="item">
                    <h2>Bank Payment</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                            <li class="breadcrumb-item active">Bank Payment</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pt_50 pb_50 gray">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div>
                    <h3 class="mb_10">Bank Information (where you have to pay):</h3>
                    <p>
                        Bank Name: ABC Bank<br>
                        Account Name: Event Management<br>
                        Account Number: 1234567890<br>
                        Branch Name: New York<br>
                        Swift Code: 1234567890
                    </p>
                </div>
                <form action="" method="post" class="mt_20">
                    <h3 class="mb_10">Transaction Information</h3>
                    <div class="form-group">
                        <textarea name="bank_transaction_info" class="form-control h_150"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="form_submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>