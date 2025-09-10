<?php

ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Initialize
$success_message = '';
$error_message = '';
$pricing_section = null;

// Generate CSRF token if not exists
if (!isset($_SESSION['home_pricing_csrf_token'])) {
    $_SESSION['home_pricing_csrf_token'] = bin2hex(random_bytes(32));
}

// Create pricing section table if not exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS homepage_sections (
        id INT PRIMARY KEY AUTO_INCREMENT,
        section_name VARCHAR(100) NOT NULL UNIQUE,
        title VARCHAR(255),
        description TEXT,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Insert default pricing section if not exists
    $stmt = $pdo->prepare("INSERT IGNORE INTO homepage_sections (section_name, title, description) VALUES (?, ?, ?)");
    $stmt->execute([
        'pricing',
        'Pricing',
        'You will find below the different pricing options for our event. Choose the one that suits you best and register now! You will have access to all sessions, unlimited coffee and food, and the opportunity to meet with your favorite speakers.'
    ]);
} catch (Exception $e) {
    $error_message = "Database setup error: " . $e->getMessage();
}

// Fetch current pricing section data
try {
    $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE section_name = 'pricing'");
    $stmt->execute();
    $pricing_section = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching pricing section data: " . $e->getMessage();
}

// Handle UPDATE operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pricing_section'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_pricing_csrf_token']) || $_POST['home_pricing_csrf_token'] !== $_SESSION['home_pricing_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title)) {
            throw new Exception("Title cannot be empty.");
        }

        if (empty($description)) {
            throw new Exception("Description cannot be empty.");
        }

        $stmt = $pdo->prepare("UPDATE homepage_sections SET title = :title, description = :description, is_active = :is_active WHERE section_name = 'pricing'");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':is_active' => $is_active
        ]);

        if ($stmt->rowCount() > 0) {
            $success_message = "Pricing section updated successfully!";
        } else {
            $success_message = "No changes were made.";
        }

        // Regenerate CSRF token
        $_SESSION['home_pricing_csrf_token'] = bin2hex(random_bytes(32));

        // Refresh pricing section data
        $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE section_name = 'pricing'");
        $stmt->execute();
        $pricing_section = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Fetch packages count for display
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM packages");
    $stmt->execute();
    $packages_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (Exception $e) {
    $packages_count = 0;
}

// Fetch features count for display
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM features");
    $stmt->execute();
    $features_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (Exception $e) {
    $features_count = 0;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Home Pricing Section</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <?php if ($success_message): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>

                    <!-- Info Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Packages</h4>
                                    </div>
                                    <div class="card-body">
                                        <?= $packages_count ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="far fa-star"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Features</h4>
                                    </div>
                                    <div class="card-body">
                                        <?= $features_count ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <a href="package.php" class="btn btn-primary btn-sm mr-2">
                                        <i class="fas fa-box"></i> Manage Packages
                                    </a>
                                    <a href="feature.php" class="btn btn-success btn-sm">
                                        <i class="fas fa-star"></i> Manage Features
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Pricing Section Content</h4>
                            <div class="card-header-action">
                                <?php if ($pricing_section && $pricing_section['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($pricing_section): ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="home_pricing_csrf_token"
                                        value="<?= $_SESSION['home_pricing_csrf_token'] ?>">

                                    <div class="form-group">
                                        <label for="title">Section Title <span class="text-danger">*</span></label>
                                        <input type="text" 
                                                name="title" 
                                                id="title"
                                                value="<?= htmlspecialchars($pricing_section['title']) ?>"
                                                class="form-control" 
                                                placeholder="Enter section title"
                                                maxlength="255"
                                                required>
                                        <small class="form-text text-muted">This will be displayed as the main heading of the pricing section.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Section Description <span class="text-danger">*</span></label>
                                        <textarea name="description" 
                                                    id="description"
                                                    class="form-control"
                                                    placeholder="Enter section description"
                                                    required style="min-height: 100px" ><?= htmlspecialchars($pricing_section['description']) ?></textarea>
                                        <small class="form-text text-muted">This description will appear below the title to explain your pricing options.</small>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                    name="is_active" 
                                                    id="is_active" 
                                                    class="custom-control-input"
                                                    <?= $pricing_section['is_active'] ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="is_active">Show this section on homepage</label>
                                        </div>
                                        <small class="form-text text-muted">Uncheck to hide the entire pricing section from homepage.</small>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="update_pricing_section" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Pricing Section
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Pricing section data not found. Please refresh the page or contact administrator.
                                    </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-question-circle"></i> How it Works</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <i class="fas fa-heading fa-3x text-primary mb-3"></i>
                                        <h6>Section Title & Description</h6>
                                        <p class="text-muted">Managed here. This is the main content at the top of your pricing section.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <i class="fas fa-box fa-3x text-success mb-3"></i>
                                        <h6>Packages</h6>
                                        <p class="text-muted">Managed in <a href="packages.php">Packages section</a>. These are your pricing plans.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                                        <h6>Features</h6>
                                        <p class="text-muted">Managed in <a href="features.php">Features section</a>. These are assigned to packages.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("layouts/footer.php"); ?>


<?php ob_end_flush(); ?>