<?php
ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Send Email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


include(__DIR__ . '/../config/helpers.php');

// User Data

$userData = null;
$admin = null;

if (isset($_SESSION['user']['id'])) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->execute(['id' => $_SESSION['user']['id']]);
  $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get admin user(s)
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = :role");
$stmt->execute(['role' => 'admin']);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form_submit'])) {

  try {
    if ($_POST['name'] == '') {
      throw new Exception("Name can not be empty");
    }
    if ($_POST['email'] == '') {
      throw new Exception("Email can not be empty");
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Please enter a valid email");
    }
    if ($_POST['subject'] == '') {
      throw new Exception("Subject can not be empty");
    }
    if ($_POST['message'] == '') {
      throw new Exception("Message can not be empty");
    }

    $email_message = "<h3>Visitor Information</h3>";
    $email_message .= '<p><strong>Name:</strong><br>' . $_POST['name'] . '</p>';
    $email_message .= '<p><strong>Email:</strong><br>' . $_POST['email'] . '</p>';
    $email_message .= '<p><strong>Subject:</strong><br>' . $_POST['subject'] . '</p>';
    $email_message .= '<p><strong>Message:</strong><br>' . $_POST['message'] . '</p>';

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
      $mail->addAddress($admin['email']);
      $mail->isHTML(true);
      $mail->Subject = 'Contact Form Message';
      $mail->Body = $email_message;
      $mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $_SESSION['success_message'] = "Your message has been sent successfully";
    header("location: " . BASE_URL . "contact");
    exit;

  } catch (Exception $e) {
    $error_message = $e->getMessage();
    $_SESSION['error_message'] = $error_message;
    header("location: " . BASE_URL . "contact.php");
    exit;
  }
}

?>
<div id="contacts" class="pt_70 pb_50 white">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-sm-12">
        <div class="contact">
          <form class="form" method="post" action="">
            <div class="row">
              <div class="form-group col-md-6">
                <input name="name" class="form-control" placeholder="Name *" type="text">
              </div>
              <div class="form-group col-md-6">
                <input name="email" class="form-control" placeholder="Email *" type="email">
              </div>
              <div class="form-group col-md-12">
                <input name="subject" class="form-control" placeholder="Subject *" type="text">
              </div>
              <div class="form-group col-md-12">
                <textarea rows="3" name="message" class="form-control" placeholder="Message *"></textarea>
              </div>
              <div class="col-md-12">
                <div class="actions">
                  <button type="submit" class="btn btn-lg btn-con-bg" name="contact_form_submit">Send Message</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?php if ($userData): ?>
        <div class="col-lg-4 col-sm-12">
          <div class="contact-info">
            <div class="contact-inner-box">
              <div class="icon">
                <div class="contact-inner-icon">
                  <i class="fa fa-map-marker"></i>
                </div>
              </div>
              <div class="text">
                <div class="contact-inner-text">
                  Address: <br /><span><?php echo htmlspecialchars($userData['address'] ?? 'Not Available'); ?></span>
                </div>
              </div>
            </div>
            <div class="contact-inner-box">
              <div class="icon">
                <div class="contact-inner-icon">
                  <i class="fa fa-envelope-o"></i>
                </div>
              </div>
              <div class="text">
                <div class="contact-inner-text">
                  Email: <br /><span><?php echo htmlspecialchars($userData['email']); ?></span>
                </div>
              </div>
            </div>
            <div class="contact-inner-box">
              <div class="icon">
                <div class="contact-inner-icon">
                  <i class="fa fa-phone"></i>
                </div>
              </div>
              <div class="text">
                <div class="contact-inner-text">
                  Phone: <br /><span><?php echo htmlspecialchars($userData['phone'] ?? 'Not Available'); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>