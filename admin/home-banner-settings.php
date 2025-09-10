<?php
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Initialize
$success_message = '';
$error_message = '';

// Generate CSRF token if not exists
if (!isset($_SESSION['home_banner_update_csrf_token'])) {
    $_SESSION['home_banner_update_csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch current banner data
try {
    $stmt = $pdo->prepare("SELECT * FROM home_banners LIMIT 1");
    $stmt->execute();
    $bannerData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Format the date properly for HTML date input (YYYY-MM-DD)
    if (!empty($bannerData['event_date'])) {
        $bannerData['event_date'] = date('Y-m-d', strtotime($bannerData['event_date']));
    }
    // If no banner exists, create one
    if (!$bannerData) {
        $stmt = $pdo->prepare("INSERT INTO home_banners (subheading, heading, description, event_date) VALUES ('', '', '', NULL)");
        $stmt->execute();

        $stmt = $pdo->prepare("SELECT * FROM home_banners LIMIT 1");
        $stmt->execute();
        $bannerData = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $error_message = "Error fetching banner data: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['home_banner_settings_form'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_banner_update_csrf_token']) || $_POST['home_banner_update_csrf_token'] !== $_SESSION['home_banner_update_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        // Fetch and sanitize
        $subheading = trim($_POST['subheading'] ?? '');
        $heading = trim($_POST['heading'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');

        // Validation
        if (empty($heading) || empty($event_date)) {
            throw new Exception("Heading and Event Date are required.");
        }

        // Validate event date is not in the past
        $current_date = date('Y-m-d');
        if ($event_date < $current_date) {
            throw new Exception("Event Date cannot be in the past. Please select a future date.");
        }

        $pdo->beginTransaction();

        // Update banner - using the banner's ID
        $stmt = $pdo->prepare("UPDATE home_banners SET subheading = :subheading, heading = :heading, description = :description, event_date = :event_date WHERE id = :id");
        $stmt->execute([
            ':subheading' => $subheading,
            ':heading' => $heading,
            ':description' => $description,
            ':event_date' => $event_date,
            ':id' => $bannerData['id']
        ]);

        // Image upload logic
        if (isset($_FILES['background']) && $_FILES['background']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['background']['tmp_name'];
            $fileName = $_FILES['background']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $mimeType = mime_content_type($fileTmp);
            $fileSize = $_FILES['background']['size'];

            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($fileExt, $allowedExts) || !in_array($mimeType, $allowedMimes)) {
                throw new Exception("Invalid image format.");
            }

            if ($fileSize > 2 * 1024 * 1024) {
                throw new Exception("Image size must be under 2MB.");
            }

            if (!is_dir('uploads'))
                mkdir('uploads', 0755, true);

            if (!empty($bannerData['background']))
                @unlink('uploads/' . $bannerData['background']);

            $newFileName = uniqid('background_', true) . '.' . $fileExt;
            move_uploaded_file($fileTmp, 'uploads/' . $newFileName);

            $stmt = $pdo->prepare("UPDATE home_banners SET background = :background WHERE id = :id");
            $stmt->execute([':background' => $newFileName, ':id' => $bannerData['id']]);
            $bannerData['background'] = $newFileName;
        }

        $pdo->commit();

        // Refresh data and CSRF token
        $stmt = $pdo->prepare("SELECT * FROM home_banners WHERE id = :id");
        $stmt->execute([':id' => $bannerData['id']]);
        $bannerData = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['home_banner_update_csrf_token'] = bin2hex(random_bytes(32));
        $success_message = "Banner updated successfully!";
    } catch (Exception $e) {
        if ($pdo->inTransaction())
            $pdo->rollBack();
        $error_message = $e->getMessage();
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Home Banner Settings</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($success_message); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($error_message); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="" method="post" enctype="multipart/form-data">
                                <!-- CSRF Protection -->
                                <input type="hidden" name="home_banner_update_csrf_token"
                                    value="<?php echo $_SESSION['home_banner_update_csrf_token']; ?>">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <!-- Background Image -->
                                            <?php
                                            $background = $bannerData['background'] ?? '';
                                            $background_url = !empty($background) ? ADMIN_URL . "/uploads/$background" : ADMIN_URL . "/uploads/banner-home.jpg";
                                            ?>
                                            <img src="<?php echo htmlspecialchars($background_url); ?>"
                                                alt="Profile background" class="profile-background w-25">
                                            <input type="file" placeholder="Upload Background Image"
                                                class="mt_10 d-block" name="background" accept="image/*">
                                            <small class="form-text d-block text-muted">Allowed formats: JPG, PNG. Max
                                                size: 2MB.</small>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Sub Heading</label>
                                            <input type="text" class="form-control" name="subheading"
                                                value="<?= htmlspecialchars($bannerData['subheading'] ?? '') ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Heading *</label>
                                            <input type="text" class="form-control" name="heading"
                                                value="<?= htmlspecialchars($bannerData['heading'] ?? '') ?>" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3"
                                                placeholder="Enter description..."><?= htmlspecialchars($bannerData['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Event Date</label>
                                            <input type="date" class="form-control" name="event_date"
                                                value="<?= !empty($bannerData['event_date']) ? htmlspecialchars($bannerData['event_date']) : '' ?>"
                                                min="<?= date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="mb-4">
                                            <button type="submit" name="home_banner_settings_form"
                                                class="btn btn-primary">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include("layouts/footer.php"); ?>