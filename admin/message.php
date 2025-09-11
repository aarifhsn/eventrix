<?php
ob_start();
// admin/message.php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../config/helpers.php');

// Get user ID from URL
$user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$user_id) {
    echo "Invalid user ID";
    exit;
}

// Get user information
$statement = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role != 'admin'");
$statement->execute([$user_id]);
$user_data = $statement->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    echo "User not found";
    exit;
}

// Handle admin reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_reply_submit'])) {
    try {
        if (trim($_POST['admin_message']) == '') {
            throw new Exception("Reply message cannot be empty");
        }

        // Insert admin reply (admin_id = 1 means it's from admin)
        $statement = $pdo->prepare("INSERT INTO messages (user_id, admin_id, message, date_time) VALUES (?, ?, ?, ?)");
        $statement->execute([$user_id, 1, trim($_POST['admin_message']), date('Y-m-d H:i:s')]);

        // Send email notification to user
        if (!empty($user_data['email'])) {
            $link = BASE_URL . '/user-messages';
            $email_message = "You have received a reply from the administrator:<br><br>";
            $email_message .= "<strong>Message:</strong><br>" . nl2br(htmlspecialchars($_POST['admin_message'])) . "<br><br>";
            $email_message .= '<a href="' . $link . '">View Messages</a>';

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
                $mail->addAddress($user_data['email']);
                $mail->isHTML(true);
                $mail->Subject = 'Administrator Reply - ' . SITE_NAME;
                $mail->Body = $email_message;
                $mail->send();
            } catch (Exception $e) {
                error_log("Email notification failed: {$mail->ErrorInfo}");
            }
        }

        $_SESSION['success_message'] = "Reply sent successfully";
        header("Location: message.php?id=" . $user_id);
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}

?>

<div class="admin-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Conversation with <?php echo htmlspecialchars($user_data['name']); ?></h3>
                    <a href="messages.php" class="btn btn-secondary">← Back to All Messages</a>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Message History -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Message History</h5>
                            </div>
                            <div class="card-body">
                                <div class="messages-container">
                                    <?php
                                    // Get all messages for this user
                                    $statement = $pdo->prepare("SELECT 
                                                                    m.*,
                                                                    u.name as user_name,
                                                                    u.photo as user_photo
                                                                FROM messages m
                                                                LEFT JOIN users u ON m.user_id = u.id
                                                                WHERE m.user_id = ?
                                                                ORDER BY m.id ASC");
                                    $statement->execute([$user_id]);
                                    $messages = $statement->fetchAll(PDO::FETCH_ASSOC);

                                    if (!$messages) {
                                        echo '<p class="text-muted">No messages found.</p>';
                                    } else {
                                        foreach ($messages as $message) {
                                            $isAdminMessage = ($message['admin_id'] == 1);
                                            ?>
                                            <div
                                                class="message-bubble <?php echo $isAdminMessage ? 'admin-message' : 'user-message'; ?> mb-3">
                                                <div class="message-header">
                                                    <strong>
                                                        <?php echo $isAdminMessage ? 'Administrator' : htmlspecialchars($message['user_name']); ?>
                                                    </strong>
                                                    <span
                                                        class="badge <?php echo $isAdminMessage ? 'bg-danger' : 'bg-primary'; ?> ms-2 text-white">
                                                        <?php echo $isAdminMessage ? 'Admin' : 'User'; ?>
                                                    </span>
                                                    <small class="text-muted ms-3">
                                                        <?php echo date('M j, Y g:i A', strtotime($message['date_time'])); ?>
                                                    </small>
                                                </div>
                                                <div class="message-content">
                                                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Send Reply</h5>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <textarea name="admin_message" class="form-control" rows="6"
                                            placeholder="Write your reply here..." required></textarea>
                                    </div>
                                    <button type="submit" name="admin_reply_submit" class="btn btn-primary">
                                        <i class="fa fa-paper-plane"></i> Send Reply
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- User Information -->
                        <div class="card">
                            <div class="card-header">
                                <h5>User Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center d-flex justify-content-center align-items-center mb-3">
                                    <?php if (!empty($user_data['photo'])): ?>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $user_data['photo']; ?>"
                                            class="rounded-circle" width="100" height="100" alt="User Photo">
                                    <?php else: ?>
                                        <h3 class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold m-0"
                                            style="width: 40px; height: 40px; font-size: 16px;">
                                            <?php echo getUserInitials($user_data['name']); ?>
                                        </h3>
                                    <?php endif; ?>
                                </div>

                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td><?php echo htmlspecialchars($user_data['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?php echo htmlspecialchars($user_data['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?php echo htmlspecialchars($user_data['phone'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Joined:</strong></td>
                                        <td><?php echo date('M j, Y', strtotime($user_data['created_at'] ?? $user_data['date_time'] ?? '')); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .messages-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 15px;
    }

    .message-bubble {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .user-message {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        margin-left: 0;
        margin-right: 50px;
    }

    .admin-message {
        background-color: #edf3f3ff;
        border-left: 4px solid #dc3545;
        margin-left: 50px;
        margin-right: 0;
    }

    .message-header {
        margin-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 5px;
    }

    .message-content {
        line-height: 1.6;
    }

    .card {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>

<?php include(__DIR__ . '/../includes/footer.php'); ?>