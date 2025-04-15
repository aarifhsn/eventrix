<?php
session_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../config/helpers.php');

// Load PHPMailer classes (assumes Composer autoload or manual includes)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect if already logged in
if (isset($_SESSION['user'])) {
  header('Location: ' . BASE_URL . 'user-dashboard');
  exit;
}

// Define default messages
$error_message = '';
$success_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_forget_password_form'])) {
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
    $stmt->execute([$email, 'user']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      throw new Exception("User not found with that email.");
    }

    // Generate token and update user record
    $token = bin2hex(random_bytes(16));
    $updateToken = $pdo->prepare("UPDATE users SET token = ? WHERE email = ?");
    $updateToken->execute([$token, $email]);

    // Prepare the password reset link
    $resetLink = BASE_URL . "reset-password?email=" . urlencode($email) . "&token=" . $token;
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

<div id="Loginsection" class="pt_50 pb_50 gray Loginsection">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5">
        <div class="login-register-bg">
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
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
              <form action="" class="registerd" method="post">
                <div class="form-group">
                  <input class="form-control" name="email" placeholder="Email Address" type="email" />
                </div>
                <div class="form-group">
                  <button type="submit" name="user_forget_password_form">SUBMIT</button>
                </div>
                <div class="form-group bottom">
                  <a href="<?php echo BASE_URL; ?>login">Back to login page</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>