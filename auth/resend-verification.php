<?php
session_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

$email = $_SESSION['unverified_email'] ?? null;
$token = $_SESSION['unverified_token'] ?? null;
$resent = false;

if ($email && $token) {
    // Send the email logic
    $verificationLink = BASE_URL . "registration-verify?email=" . urlencode($email) . "&token=" . $token;

    // Assume you have a mail function
    $subject = "Verify Your Email";
    $message = "Click the link below to verify your email:\n\n" . $verificationLink;

    mail($email, $subject, $message); // 🛠 Replace with your mailer logic
    $resent = true;

    // Optional: Clear the session values after sending
    unset($_SESSION['unverified_email'], $_SESSION['unverified_token']);
}
?>

<div class="container pt_50 pb_50 gray">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <?php if ($resent): ?>
                <div class="alert alert-success">
                    A verification link has been sent to your email.
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    Something went wrong or your session has expired.
                </div>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>login" class="btn btn-primary mt-3">Back to Login</a>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>