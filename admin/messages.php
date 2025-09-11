<?php
// admin/messages.php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../config/helpers.php');

// Get all users who have sent messages with their latest message info
$statement = $pdo->prepare("SELECT 
                                u.id as user_id,
                                u.name as user_name,
                                u.email as user_email,
                                u.photo as user_photo,
                                (SELECT COUNT(*) FROM messages WHERE user_id = u.id) as total_messages,
                                (SELECT COUNT(*) FROM messages WHERE user_id = u.id AND admin_id = 0) as user_messages,
                                (SELECT COUNT(*) FROM messages WHERE user_id = u.id AND admin_id = 1) as admin_replies,
                                (SELECT message FROM messages WHERE user_id = u.id ORDER BY id DESC LIMIT 1) as latest_message,
                                (SELECT date_time FROM messages WHERE user_id = u.id ORDER BY id DESC LIMIT 1) as latest_message_time,
                                (SELECT admin_id FROM messages WHERE user_id = u.id ORDER BY id DESC LIMIT 1) as latest_message_type
                            FROM users u 
                            WHERE u.role != 'admin' 
                            AND u.id IN (SELECT DISTINCT user_id FROM messages)
                            ORDER BY latest_message_time DESC");
$statement->execute();
$conversations = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="admin-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Message Center</h3>
                    <div class="badge bg-info fs-6 text-white">
                        <?php echo count($conversations); ?> Active Conversations
                    </div>
                </div>

                <?php if (empty($conversations)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No messages found. Users haven't started any conversations yet.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($conversations as $conversation): ?>
                            <div class="col-lg-6 col-xl-4 mb-4">
                                <div class="card conversation-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="user-avatar me-3">
                                                <?php if (!empty($conversation['user_photo'])): ?>
                                                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo $conversation['user_photo']; ?>"
                                                        class="rounded-circle" width="50" height="50" alt="User">
                                                <?php else: ?>
                                                    <h3 class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold m-0"
                                                        style="width: 40px; height: 40px; font-size: 16px;">
                                                        <?php echo getUserInitials($conversation['user_name']); ?>
                                                    </h3>
                                                <?php endif; ?>
                                            </div>
                                            <div class="user-info flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($conversation['user_name']); ?>
                                                </h6>
                                                <small
                                                    class="text-muted"><?php echo htmlspecialchars($conversation['user_email']); ?></small>
                                            </div>
                                            <?php if ($conversation['latest_message_type'] == 0): ?>
                                                <span class="badge bg-warning">New</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="message-preview mb-3">
                                            <div class="latest-message">
                                                <small class="text-muted">
                                                    <?php echo $conversation['latest_message_type'] == 1 ? 'You replied:' : 'User wrote:'; ?>
                                                </small>
                                                <p class="mb-2">
                                                    <?php
                                                    $preview = htmlspecialchars($conversation['latest_message']);
                                                    echo strlen($preview) > 100 ? substr($preview, 0, 100) . '...' : $preview;
                                                    ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo time_ago($conversation['latest_message_time']); ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div class="conversation-stats mb-3">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="stat-item">
                                                        <div class="stat-number"><?php echo $conversation['total_messages']; ?>
                                                        </div>
                                                        <div class="stat-label">Total</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="stat-item">
                                                        <div class="stat-number text-primary">
                                                            <?php echo $conversation['user_messages']; ?>
                                                        </div>
                                                        <div class="stat-label">From User</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="stat-item">
                                                        <div class="stat-number text-success">
                                                            <?php echo $conversation['admin_replies']; ?>
                                                        </div>
                                                        <div class="stat-label">Your Replies</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <a href="message.php?id=<?php echo $conversation['user_id']; ?>"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-comments me-1"></i>
                                                View Conversation
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .conversation-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .conversation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .user-avatar img {
        object-fit: cover;
        border: 2px solid #f8f9fa;
    }

    .message-preview {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }

    .latest-message p {
        font-size: 14px;
        line-height: 1.4;
    }

    .conversation-stats {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
    }

    .stat-item {
        padding: 5px;
    }

    .stat-number {
        font-size: 20px;
        font-weight: bold;
        color: #495057;
    }

    .stat-label {
        font-size: 11px;
        color: #6c757d;
        text-transform: uppercase;
    }

    .admin-section {
        padding: 30px 0;
    }
</style>

<?php
// Helper function for time ago
function time_ago($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Calculate weeks without modifying the DateInterval object
    $weeks = floor($diff->d / 7);
    $days = $diff->d - ($weeks * 7);

    $string = array();

    if ($diff->y) {
        $string[] = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
    }
    if ($diff->m) {
        $string[] = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
    }
    if ($weeks) {
        $string[] = $weeks . ' week' . ($weeks > 1 ? 's' : '');
    }
    if ($days) {
        $string[] = $days . ' day' . ($days > 1 ? 's' : '');
    }
    if ($diff->h) {
        $string[] = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
    }
    if ($diff->i) {
        $string[] = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    }
    if ($diff->s) {
        $string[] = $diff->s . ' second' . ($diff->s > 1 ? 's' : '');
    }

    if (!$full && !empty($string)) {
        $string = array_slice($string, 0, 1);
    }

    return !empty($string) ? implode(', ', $string) . ' ago' : 'just now';
}

include(__DIR__ . '/../includes/footer.php'); ?>