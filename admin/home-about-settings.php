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
if (!isset($_SESSION['home_about_page_csrf_token'])) {
    $_SESSION['home_about_page_csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch current banner data
try {
    $stmt = $pdo->prepare("SELECT * FROM home_abouts LIMIT 1");
    $stmt->execute();
    $aboutData = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no about section exists, create one
    if (!$aboutData) {
        $stmt = $pdo->prepare("INSERT INTO home_abouts (heading, description, button_text, button_url, status) VALUES (:heading, :description, :button_text, :button_url, :status)");
        $stmt->execute([
            ':heading' => '',
            ':description' => '',
            ':button_text' => '',
            ':button_url' => '',
            ':status' => 1
        ]);

        $stmt = $pdo->prepare("SELECT * FROM home_abouts LIMIT 1");
        $stmt->execute();
        $aboutData = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $error_message = "Error fetching about section data: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['home_about_settings_form'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_about_page_csrf_token']) || $_POST['home_about_page_csrf_token'] !== $_SESSION['home_about_page_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        // Fetch and sanitize
        $heading = trim($_POST['heading'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $button_text = trim($_POST['button_text'] ?? '');
        $button_url = trim($_POST['button_url'] ?? '');
        $status = ($_POST['status'] == '1') ? 1 : 0;

        // Validation
        if (empty($heading) || empty($description)) {
            throw new Exception("Heading and Description are required.");
        }

        $pdo->beginTransaction();

        // Update banner - using the banner's ID
        $stmt = $pdo->prepare("UPDATE home_abouts SET heading = :heading, description = :description, button_text = :button_text, button_url = :button_url, status = :status WHERE id = :id");
        $stmt->execute([
            'heading' => $heading,
            'description' => $description,
            'button_text' => $button_text,
            'button_url' => $button_url,
            'status' => $status,
            'id' => $aboutData['id']
        ]);

        // Image upload logic
        try {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $newFileName = uploadImage('photo', 'uploads', 2 * 1024 * 1024, ['jpg', 'jpeg', 'png', 'webp']);

                $stmt = $pdo->prepare("UPDATE home_abouts SET photo = :photo WHERE id = :id");
                $stmt->execute([':photo' => $newFileName, ':id' => $aboutData['id']]);
                $aboutData['photo'] = $newFileName;
            }
        } catch (Exception $e) {
            $error_message = $e->getMessage();
        }

        $pdo->commit();

        // Refresh data and CSRF token
        $stmt = $pdo->prepare("SELECT * FROM home_abouts WHERE id = :id");
        $stmt->execute([':id' => $aboutData['id']]);
        $aboutData = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['home_about_page_csrf_token'] = bin2hex(random_bytes(32));
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
            <h1>Edit Home About Section Settings</h1>
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
                                <input type="hidden" name="home_about_page_csrf_token"
                                    value="<?php echo $_SESSION['home_about_page_csrf_token']; ?>">

                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="mb-4">
                                            <label class="form-label">Heading *</label>
                                            <input type="text" class="form-control" name="heading"
                                                value="<?= htmlspecialchars($aboutData['heading'] ?? '') ?>" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3"
                                                placeholder="Enter description..."><?= htmlspecialchars($aboutData['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-4">
                                                    <label class="form-label">Button Text</label>
                                                    <input type="text" class="form-control" name="button_text"
                                                        value="<?= htmlspecialchars($aboutData['button_text'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-4">
                                                    <label class="form-label">Button URL</label>
                                                    <input type="text" class="form-control" name="button_url"
                                                        value="<?= htmlspecialchars($aboutData['button_url'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-4">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="1" <?= ($aboutData['status'] ?? 0) == 1 ? 'selected' : '' ?>>Show</option>
                                                        <option value="0" <?= ($aboutData['status'] ?? 0) == 0 ? 'selected' : '' ?>>Hide</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="mb-4">
                                            <!-- Background Image -->
                                            <?php
                                            $photo = $aboutData['photo'] ?? '';
                                            $photo_url = ADMIN_URL . "/uploads/$photo";

                                            if (!empty($photo)) {
                                                echo '<img src="' . htmlspecialchars($photo_url) . '" alt="Background Photo" class="profile-photo w_100_p">';
                                            }

                                            ?>

                                            <input type="file" placeholder="Upload photo" class="mt_10 d-block"
                                                name="photo" accept="image/*">
                                            <small class="form-text d-block text-muted">Allowed formats: JPG, PNG. Max
                                                size: 2MB.</small>
                                        </div>

                                        <div class="mb-4">
                                            <button type="submit" name="home_about_settings_form"
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