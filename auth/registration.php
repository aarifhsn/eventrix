<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Generate CSRF token
if (empty($_SESSION['csrf_token_for_registration'])) {
  $_SESSION['csrf_token_for_registration'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_registration_form'])) {

  if (!isset($_POST['csrf_token_for_registration']) || $_POST['csrf_token_for_registration'] !== $_SESSION['csrf_token_for_registration']) {
    $_SESSION['error_message'] = "Security Validation Failed.";
    header("Location: " . BASE_URL . "registration");
    exit;
  }

  // Basic input sanitization
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirmPassword = $_POST['confirm_password'] ?? '';

  // Store sanitized values in session for form re-submission
  $_SESSION['name'] = htmlspecialchars($name);
  $_SESSION['email'] = htmlspecialchars($email);

  try {
    // Basic validation
    if (empty($name))
      throw new Exception("Name is required.");
    if (empty($email))
      throw new Exception("Email is required.");
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      throw new Exception("Invalid email format.");
    if (empty($password) || empty($confirmPassword))
      throw new Exception("Both password fields are required.");
    if ($password !== $confirmPassword)
      throw new Exception("Passwords do not match.");

    // Password strength validation
    if (strlen($password) < 8) {
      throw new Exception("Password must be at least 8 characters long.");
    }

    if (
      !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) ||
      !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)
    ) {
      throw new Exception("Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.");
    }

    // Check for duplicate email
    $statement = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $statement->execute([$email]);
    if ($statement->rowCount() > 0)
      throw new Exception("Email is already registered.");

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));
    $status = 0;

    // Insert user with transaction for safety
    // Default photo
    $defaultPhoto = 'default.jpg';

    $statement = $pdo->prepare("INSERT INTO users (photo, name, email, password, token, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    $statement->execute([$defaultPhoto, $name, $email, $hashedPassword, $token, 'user', $status]);

    // Prepare email
    $verifyLink = BASE_URL . "registration-verify?email=" . urlencode($email) . "&token=" . $token;
    $emailBody = <<<EMAIL
            <p>Hi $name,</p>
            <p>Please click the link below to verify your registration:</p>
            <p><a href="$verifyLink">Verify Email</a></p>
EMAIL;

    // Send email
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = SMTP_HOST;
      $mail->SMTPAuth = true;
      $mail->Username = SMTP_USERNAME;
      $mail->Password = SMTP_PASSWORD;
      $mail->SMTPSecure = SMTP_ENCRYPTION;
      $mail->Port = SMTP_PORT;
      $mail->setFrom(SMTP_FROM, 'Site Registration');
      $mail->addAddress($email);
      $mail->addReplyTo(SMTP_FROM);
      $mail->isHTML(true);
      $mail->Subject = 'Email Verification';
      $mail->Body = $emailBody;
      $mail->send();
    } catch (Exception $e) {
      // Log email error but don't show technical details to user
      error_log("Email sending failed: " . $mail->ErrorInfo);
      // Still continue with success message
    }

    $_SESSION['success_message'] = "Registration successful! Please check your email to verify your account.";
    unset($_SESSION['name'], $_SESSION['email']);
    header("Location: " . BASE_URL . "registration");
    exit;

  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header("Location: " . BASE_URL . "registration");
    exit;
  }
}

// Regenerate CSRF token for security
$_SESSION['csrf_token_for_registration'] = bin2hex(random_bytes(32));
?>

<div id="Loginsection" class="pt_50 pb_50 gray Loginsection">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5">
        <div class="login-register-bg">
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">

              <?php if (!empty($_SESSION['error_message'])): ?>
                <div class="alert alert-danger lh-base">
                  <?= $_SESSION['error_message'];
                  unset($_SESSION['error_message']); ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($_SESSION['success_message'])): ?>
                <div class="alert alert-success lh-base">
                  <?= $_SESSION['success_message'];
                  unset($_SESSION['success_message']); ?>
                </div>
              <?php endif; ?>

              <form action="" class="registerd" method="post">
                <!-- CSRF Protection -->
                <input type="hidden" name="csrf_token_for_registration"
                  value="<?= $_SESSION['csrf_token_for_registration']; ?>">

                <div class="form-group">
                  <input class="form-control" name="name" placeholder="Name" type="text"
                    value="<?php echo $_SESSION['name'] ?? ''; ?>" />
                </div>
                <div class="form-group">
                  <input class="form-control" name="email" placeholder="Email Address" type="text"
                    value="<?= $_SESSION['email'] ?? '' ?>" />
                </div>
                <div class="form-group">
                  <input class="form-control" name="password" placeholder="Password" type="password" />
                </div>
                <div class="form-group">
                  <input class="form-control" name="confirm_password" placeholder="Confirm Password" type="password" />
                </div>
                <div class="form-group">
                  <button type="submit" name="user_registration_form">REGISTER NOW</button>
                </div>
                <div class="form-group bottom">
                  <a href="<?php echo BASE_URL; ?>login">Are you a already member? Login now!</a>
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
<?php ob_end_flush(); ?>