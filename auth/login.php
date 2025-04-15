<?php
session_start();

ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

if (isset($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'user-dashboard');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_login_form'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        // Basic validation
        if (empty($email)) {
            throw new Exception("Email cannot be empty.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (empty($password)) {
            throw new Exception("Password cannot be empty.");
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, 'user']);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData || !password_verify($password, $userData['password'])) {
            throw new Exception("Incorrect email or password.");
        }

        // Check if user has verified their email
        if ($userData['status'] == 0) {
            $_SESSION['unverified_email'] = $email;
            $_SESSION['unverified_token'] = $userData['token'];
            header('Location: ' . BASE_URL . 'resend-verification');
            exit;
        }

        // Login success
        $_SESSION['user'] = $userData;
        header('Location: ' . BASE_URL . 'user-dashboard');
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<div id="Loginsection" class="pt_50 pb_50 gray Loginsection">
    <div class="container">

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message w-100 text-center">
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="error text-danger m-3">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="login-register-bg">
                    <div class="row">

                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form action="" class="registerd" method="post">
                                <div class="form-group">
                                    <input class="form-control" name="email" placeholder="Email Address" type="text"
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="password" placeholder="Password" type="password">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="user_login_form">
                                        LOGIN
                                    </button>
                                </div>
                                <div class="form-group bottom">
                                    <a href="<?php echo BASE_URL; ?>forget-password">Forgot Password?</a>
                                    <br>
                                    <a href="<?php echo BASE_URL; ?>registration">Create New account</a>
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