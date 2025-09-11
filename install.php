<?php

session_start();

// Check if already installed
if (file_exists('config/config.php') && !isset($_GET['force'])) {
    $config = include 'config/config.php';
    try {
        $pdo = new PDO(
            "mysql:host={$dbhost};dbname={$dbname}",
            $dbuser,
            $dbpass
        );

        // Check if admin exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            die("Installation already completed. <a href='index.php'>Go to Homepage</a>");
        }
    } catch (Exception $e) {
        // Continue with installation if connection fails
    }
}

$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;
$error = '';
$success = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        // Step 1: Database Configuration
        $host = trim($_POST['host']);
        $database = trim($_POST['database']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Save database config
            $configContent = "<?php\nreturn [\n";
            $configContent .= "    'host' => '$host',\n";
            $configContent .= "    'database' => '$database',\n";
            $configContent .= "    'username' => '$username',\n";
            $configContent .= "    'password' => '$password',\n";
            $configContent .= "    'charset' => 'utf8mb4'\n";
            $configContent .= "];";

            file_put_contents('config/config.php', $configContent);

            $_SESSION['db_config'] = compact('host', 'database', 'username', 'password');
            header('Location: install.php?step=2');
            exit;

        } catch (Exception $e) {
            $error = "Database connection failed: " . $e->getMessage();
        }

    } elseif ($step == 2) {
        // Step 2: Import Database
        $config = $_SESSION['db_config'];

        try {
            $pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']}",
                $config['username'],
                $config['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Import SQL file
            if (file_exists('eventrix.sql')) {
                $sql = file_get_contents('eventrix.sql');
                $pdo->exec($sql);
                $success = "Database tables created successfully!";
                header('Location: install.php?step=3');
                exit;
            } else {
                $error = "eventrix.sql file not found!";
            }

        } catch (Exception $e) {
            $error = "Database import failed: " . $e->getMessage();
        }

    } elseif ($step == 3) {
        // Step 3: Create Admin Account
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($name) || empty($email) || empty($password)) {
            $error = "All fields are required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address!";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters!";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            try {
                $config = $_SESSION['db_config'];
                $pdo = new PDO(
                    "mysql:host={$config['host']};dbname={$config['database']}",
                    $config['username'],
                    $config['password']
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Create admin user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, 'admin', 'active', NOW())");
                $stmt->execute([$name, $email, $hashedPassword]);

                // Clear session and redirect to completion
                unset($_SESSION['db_config']);
                header('Location: install.php?step=4');
                exit;

            } catch (Exception $e) {
                $error = "Failed to create admin account: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventrix Installation Wizard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #4C51BF;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            padding: 40px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
        }

        .step.active {
            background: #4C51BF;
            color: white;
        }

        .step.completed {
            background: #10B981;
            color: white;
        }

        .step.pending {
            background: #E5E7EB;
            color: #6B7280;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4C51BF;
            box-shadow: 0 0 0 3px rgba(76, 81, 191, 0.1);
        }

        .btn {
            background: #4C51BF;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background: #4338CA;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert.error {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .alert.success {
            background: #F0FDF4;
            color: #16A34A;
            border: 1px solid #BBF7D0;
        }

        .requirements {
            background: #F9FAFB;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .req-item {
            margin: 5px 0;
        }

        .req-item.ok {
            color: #16A34A;
        }

        .req-item.error {
            color: #DC2626;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎪 Eventrix Installation</h1>
            <p>Welcome to the Eventrix setup wizard</p>
        </div>

        <div class="content">
            <div class="step-indicator">
                <div class="step <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : 'pending'; ?>">1</div>
                <div class="step <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : 'pending'; ?>">2</div>
                <div class="step <?php echo $step >= 3 ? ($step > 3 ? 'completed' : 'active') : 'pending'; ?>">3</div>
                <div class="step <?php echo $step >= 4 ? 'completed' : 'pending'; ?>">✓</div>
            </div>

            <?php if ($error): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($step == 1): ?>
                <h2>Step 1: Database Configuration</h2>
                <p>Please provide your database connection details.</p>

                <div class="requirements">
                    <h3>System Requirements</h3>
                    <div class="req-item <?php echo version_compare(PHP_VERSION, '8.0.0') >= 0 ? 'ok' : 'error'; ?>">
                        ✓ PHP <?php echo PHP_VERSION; ?> (Required: 8.0+)
                    </div>
                    <div class="req-item <?php echo extension_loaded('pdo') ? 'ok' : 'error'; ?>">
                        <?php echo extension_loaded('pdo') ? '✓' : '✗'; ?> PDO Extension
                    </div>
                    <div class="req-item <?php echo extension_loaded('curl') ? 'ok' : 'error'; ?>">
                        <?php echo extension_loaded('curl') ? '✓' : '✗'; ?> cURL Extension
                    </div>
                    <div class="req-item <?php echo is_writable('config') ? 'ok' : 'error'; ?>">
                        <?php echo is_writable('config') ? '✓' : '✗'; ?> Config folder writable
                    </div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label>Database Host:</label>
                        <input type="text" name="host" value="localhost" required>
                    </div>
                    <div class="form-group">
                        <label>Database Name:</label>
                        <input type="text" name="database" placeholder="eventrix_db" required>
                    </div>
                    <div class="form-group">
                        <label>Database Username:</label>
                        <input type="text" name="username" placeholder="root" required>
                    </div>
                    <div class="form-group">
                        <label>Database Password:</label>
                        <input type="password" name="password">
                    </div>
                    <button type="submit" class="btn">Test Connection & Continue</button>
                </form>

            <?php elseif ($step == 2): ?>
                <h2>Step 2: Database Setup</h2>
                <p>Creating database tables and importing initial data...</p>

                <form method="POST">
                    <button type="submit" class="btn">Import Database Structure</button>
                </form>

            <?php elseif ($step == 3): ?>
                <h2>Step 3: Create Admin Account</h2>
                <p>Create your administrator account to manage Eventrix.</p>

                <form method="POST">
                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="name" placeholder="Administrator" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address:</label>
                        <input type="email" name="email" placeholder="admin@yourdomain.com" required>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="Minimum 6 characters" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password:</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn">Create Admin Account</button>
                </form>

            <?php elseif ($step == 4): ?>
                <h2>🎉 Installation Complete!</h2>
                <div class="alert success">
                    Eventrix has been successfully installed and configured!
                </div>

                <h3>What's Next?</h3>
                <ul style="margin: 20px 0; padding-left: 20px;">
                    <li>Configure payment settings in admin panel</li>
                    <li>Upload your logo and customize branding</li>
                    <li>Create your first event</li>
                    <li>Set up email notifications</li>
                </ul>

                <div style="margin-top: 30px;">
                    <a href="index.php" class="btn"
                        style="text-decoration: none; display: inline-block; text-align: center;">
                        Visit Homepage
                    </a>
                    <a href="admin/index.php" class="btn"
                        style="text-decoration: none; display: inline-block; text-align: center; margin-left: 10px; background: #10B981;">
                        Go to Admin Panel
                    </a>
                </div>

                <p style="margin-top: 20px; font-size: 14px; color: #6B7280;">
                    <strong>Security Note:</strong> Please delete or rename the install.php file for security.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>