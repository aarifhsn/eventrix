<?php
session_start();

include(__DIR__ . '/layouts/header.php');

// Load PHPMailer classes (assumes Composer autoload or manual includes)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect if already logged in
if (isset($_SESSION['admin'])) {
    header('Location: ' . ADMIN_URL . '/dashboard.php');
    exit;
}

// Define default messages
$error_message = '';
$success_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forget_password_form'])) {
    // Get the submitted email
    $email = trim($_POST['email'] ?? '');

    try {
        // Basic validation
        if (empty($email)) {
            throw new Exception("Email cannot be empty.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Check if the admin exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, 'admin']);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            throw new Exception("Admin user not found with that email.");
        }

        // Generate token and update user record
        $token = bin2hex(random_bytes(16));
        $updateToken = $pdo->prepare("UPDATE users SET token = ? WHERE email = ?");
        $updateToken->execute([$token, $email]);

        // Prepare the password reset link
        $resetLink = ADMIN_URL . "/reset-password.php?email=" . urlencode($email) . "&token=" . $token;
        $email_message = "
            <p>Hello,</p>
            <p>We received a request to reset the password for your admin account. Click the button below to proceed:</p>
            <p style='text-align: center; margin: 30px 0;'>
                <a href='{$resetLink}' style='display: inline-block; background-color: #007bff; color: #fff; padding: 12px 20px; text-decoration: none; border-radius: 5px;'>
                    Reset Your Password
                </a>
            </p>
            <p>If you didn’t request this, no action is needed. Your account is still safe.</p>
            <p>Regards,<br>Eventrix Team</p>
        ";

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = SMTP_PORT;

            $mail->setFrom(SMTP_FROM, 'Admin Panel');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = $email_message;

            $mail->send();
            $success_message = 'Reset link has been sent. Please check your email.';
        } catch (Exception $e) {
            // Mail error
            throw new Exception("Could not send email. Mailer Error: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        // Show validation or processing errors
        $error_message = $e->getMessage();
    }
}
?>

<section class="section">
    <div class="container container-login">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary border-box">
                    <div class="card-header card-header-auth">
                        <h4 class="text-center">Reset Password</h4>
                    </div>
                    <div class="card-body card-body-auth">

                        <!-- Show error or success messages -->
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php elseif (!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <!-- CSRF Protection -->
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

                            <!-- Email Input -->
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" autofocus>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" name="forget_password_form"
                                    class="btn btn-primary btn-lg w_100_p">
                                    Send Password Reset Link
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?php echo ADMIN_URL; ?>/login.php">
                                        Back to login page
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include("layouts/footer.php"); ?>