<?php
session_start();

ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');
include(__DIR__ . '/../config/helpers.php');

if (!isset($_SESSION['user'])) {
  header('Location: ' . BASE_URL . 'login');
  exit;
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Get admin data
$statement = $pdo->prepare("SELECT * FROM users WHERE role='admin'");
$statement->execute();
$admin_data = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_submit_form'])) {
  try {
    if (trim($_POST['message']) == '') {
      throw new Exception("Message can not be empty");
    }

    // Insert user message (admin_id = 0 means it's from user)
    $statement = $pdo->prepare("INSERT INTO messages (user_id, admin_id, message, date_time) VALUES (?,?,?,?)");
    $statement->execute([$_SESSION['user']['id'], 0, trim($_POST['message']), date('Y-m-d H:i:s')]);

    // Send email notification to admin
    if (!empty($admin_data)) {
      $link = ADMIN_URL . '/message.php?id=' . $_SESSION['user']['id'];
      $email_message = "You have a new message from " . $_SESSION['user']['name'] . ": <br><br>";
      $email_message .= "<strong>Message:</strong><br>" . nl2br(htmlspecialchars($_POST['message'])) . "<br><br>";
      $email_message .= '<a href="' . $link . '">View and Reply</a>';

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
        $mail->addAddress($admin_data[0]['email']);
        $mail->isHTML(true);
        $mail->Subject = 'New message from ' . $_SESSION['user']['name'];
        $mail->Body = $email_message;
        $mail->send();
      } catch (Exception $e) {
        // Log email error but don't stop the process
        error_log("Email notification failed: {$mail->ErrorInfo}");
      }
    }

    $_SESSION['success_message'] = "Message has been sent successfully";
    header("location: " . BASE_URL . "/user-messages");
    exit;

  } catch (Exception $e) {
    $error_message = $e->getMessage();
    $_SESSION['error_message'] = $error_message;
    header("location: " . BASE_URL . "/user-messages");
    exit;
  }
}

?>
<div class="user-section pt_70 pb_70">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-2">
        <div class="user-sidebar">
          <?php include(__DIR__ . '/../templates/user-sidebar.php'); ?>
        </div>
      </div>
      <div class="col-lg-10">

        <?php if (isset($_SESSION['success_message'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <h4 class="message-heading">Write Message</h4>
        <form action="" method="post">
          <div class="mb-2">
            <textarea name="message" class="form-control h_100" cols="30" rows="10"
              placeholder="Write your message here" required></textarea>
          </div>
          <div class="mb-2 text-right">
            <button type="submit" class="btn btn-primary" name="message_submit_form">Submit</button>
          </div>
        </form>

        <h4 class="message-heading mt_40">Message History</h4>

        <?php
        // Modified query to get all messages in conversation
        $statement = $pdo->prepare("SELECT
                                        m.*,
                                        u.name as user_name,
                                        u.photo as user_photo,
                                        a.name as admin_name,
                                        a.photo as admin_photo
                                        FROM messages m
                                        LEFT JOIN users u ON m.user_id = u.id AND m.admin_id = 0
                                        LEFT JOIN users a ON m.admin_id = 1 AND a.role = 'admin'
                                        WHERE m.user_id = ?
                                        ORDER BY m.id ASC");
        $statement->execute([$_SESSION['user']['id']]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $total = $statement->rowCount();

        if (!$total) {
          echo '<div class="alert alert-info">No messages found. Start a conversation by sending your first message above.</div>';
        } else {
          echo '<div class="messages-container">';
          foreach ($result as $row) {
            $isAdminMessage = ($row['admin_id'] == 1);
            ?>
            <div class="message-item d-flex <?php echo $isAdminMessage ? 'admin-message' : 'user-message'; ?>">
              <div class="message-top">

                <div class="right">
                  <h4>
                    <?php if ($isAdminMessage): ?>
                      <?php echo !empty($admin_data) ? htmlspecialchars($admin_data[0]['name']) : 'Administrator'; ?>
                    <?php else: ?>
                      <?php echo htmlspecialchars($row['user_name'] ?? $_SESSION['user']['name']); ?>
                    <?php endif; ?>
                  </h4>
                  <h5>
                    <span class="badge text-white <?php echo $isAdminMessage ? 'bg-danger' : 'bg-primary'; ?>">
                      <?php echo $isAdminMessage ? 'Administrator' : 'Attendee'; ?>
                    </span>
                  </h5>
                  <div class="date-time">
                    <?php echo date('M j, Y g:i A', strtotime($row['date_time'])); ?>
                  </div>
                </div>
              </div>
              <div class="message-bottom">
                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
              </div>
            </div>
            <?php
          }
          echo '</div>';
        }
        ?>

      </div>
    </div>
  </div>
</div>

<style>
  .messages-container {
    max-height: 600px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fafafa;
  }

  .message-item {
    background: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .admin-message {
    border-right: 4px solid #dc3545;
    margin-left: 30%;
    flex-direction: row-reverse;
  }

  .user-message {
    border-left: 4px solid #007bff;
    margin-right: 30%;
  }

  .message-top {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }

  .message-top .left img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
  }

  .message-top .right h4 {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
  }

  .message-top .right h5 {
    margin: 5px 0;
  }

  .date-time {
    font-size: 12px;
    color: #666;
  }

  .message-bottom p {
    margin: 0;
    line-height: 1.6;
  }

  .h_100 {
    min-height: 100px;
  }
</style>

<?php include(__DIR__ . '/../includes/footer.php'); ?>