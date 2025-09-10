<?php
session_start();
include("layouts/header.php");
include("../config/helpers.php");

$error_message = '';

// Get email and token from GET parameters
$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

// Validate token & email from URL
$statement = $pdo->prepare("SELECT * FROM users WHERE email = ? AND token = ?");
$statement->execute([$_GET['email'] ?? '', $_GET['token'] ?? '']);
$userExists = $statement->rowCount();

if (!$userExists) {
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password_form'])) {
    try {

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid CSRF token. Please refresh the page and try again.");
        }

        // unset($_SESSION['csrf_token']);

        $email = $_POST['email'] ?? '';
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $retypePassword = $_POST['retype_password'] ?? '';

        if (empty($password) || empty($retypePassword)) {
            throw new Exception("Password fields cannot be empty.");
        }

        if ($password !== $retypePassword) {
            throw new Exception("Passwords do not match.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET token = '', password = ? WHERE email = ? AND token = ?");
        $stmt->execute([$hashedPassword, $email, $token]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = "Password reset successfully.";
            header('Location: ' . ADMIN_URL . '/login.php');
            exit;
        } else {
            throw new Exception("Failed to update password. Please try again.");
        }

    } catch (Exception $e) {
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
                        <h4 class="text-center">Reset Your Password</h4>
                    </div>
                    <div class="card-body card-body-auth">

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">

                            <!-- Hidden fields -->
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="email"
                                value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
                            <input type="hidden" name="token"
                                value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">


                            <!-- Password -->
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="New Password"
                                    required autofocus>
                            </div>

                            <!-- Retype Password -->
                            <div class="form-group">
                                <input type="password" class="form-control" name="retype_password"
                                    placeholder="Confirm Password" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" name="reset_password_form" class="btn btn-primary btn-lg w_100_p">
                                    Reset Password
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