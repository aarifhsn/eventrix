<?php

ob_start();
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin']) || !is_array($_SESSION['admin']) || !isset($_SESSION['admin']['id'])) {
    header('Location: login.php');
    exit;
}

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');
include(__DIR__ . '/../config/helpers.php');

// Initialize
$success_message = '';
$error_message = '';
$speaker = null;

// Generate CSRF token if not exists
if (!isset($_SESSION['speaker_section_csrf_token'])) {
    $_SESSION['speaker_section_csrf_token'] = bin2hex(random_bytes(32));
}

// Check if we're editing a speaker
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM speakers WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        $speaker = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$speaker) {
            $error_message = "Speaker not found.";
        }
    } catch (Exception $e) {
        $error_message = "Error loading speaker data: " . $e->getMessage();
    }
}

// Fetch all speakers
try {
    $stmt = $pdo->prepare("SELECT * FROM speakers ORDER BY id ASC");
    $stmt->execute();
    $speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching speakers: " . $e->getMessage();
    $speakers = [];
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM speakers WHERE id = :id");
        $stmt->execute([':id' => $_GET['delete']]);
        $success_message = "Speaker deleted successfully!";
    } catch (Exception $e) {
        $error_message = "Error deleting speaker: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_speaker_form'])) {
    try {
        // CSRF validation
        if (!isset($_POST['speaker_section_csrf_token']) || $_POST['speaker_section_csrf_token'] !== $_SESSION['speaker_section_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        // Fetch and sanitize
        $name = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $facebook = trim($_POST['facebook'] ?? '');
        $twitter = trim($_POST['twitter'] ?? '');
        $linkedin = trim($_POST['linkedin'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $photo = $_FILES['photo'] ?? null;
        $speaker_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        // Validation
        if (empty($name)) {
            throw new Exception("Name is required.");
        }

        $pdo->beginTransaction();

        // Insert new speaker
        $stmt = $pdo->prepare("INSERT INTO speakers (
                name, designation, email, phone, address, bio, website, facebook, twitter, linkedin, instagram
            ) VALUES (
                :name, :designation, :email, :phone, :address, :bio, :website, :facebook, :twitter, :linkedin, :instagram
            )");

        $params = [
            'name' => $name,
            'designation' => $designation,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'bio' => $bio,
            'website' => $website,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'linkedin' => $linkedin,
            'instagram' => $instagram
        ];
        $stmt->execute($params);
        $success_message = "Speaker added successfully!";

        // Handle image upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            try {
                $newFileName = uploadImage(
                    $_FILES['photo'],
                    'uploads',
                    ['jpg', 'jpeg', 'png', 'webp'],
                    2 * 1024 * 1024,
                    $speaker['photo'] ?? ''
                );

                $stmt = $pdo->prepare("INSERT INTO speakers SET photo = :photo WHERE id = :id");
                $stmt->execute([':photo' => $newFileName, ':id' => $speaker_id]);
            } catch (Exception $e) {
                // Continue with transaction but log error
                $error_message = "Image upload failed: " . $e->getMessage() . " Speaker was saved without the image.";
            }
        }

        $pdo->commit();

        // Refresh CSRF token
        $_SESSION['speaker_section_csrf_token'] = bin2hex(random_bytes(32));

        // Refresh speakers list
        $stmt = $pdo->prepare("SELECT * FROM speakers ORDER BY id ASC");
        $stmt->execute();
        $speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error_message = $e->getMessage();
    }
}

?>
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Speakers</h1>
        </div>

        <div class="section-body">
            <!-- Display existing speakers -->
            <?php if (!empty($speakers) && empty($_GET['id'])): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Current Speakers</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Photo</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($speakers as $speaker): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($speaker['id']) ?></td>
                                                    <td>
                                                        <?php if (!empty($speaker['photo'])): ?>
                                                            <img src="../uploads/<?= htmlspecialchars($speaker['photo']) ?>"
                                                                width="50" alt="Speaker photo">
                                                        <?php else: ?>
                                                            <span class="badge badge-light">No Image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($speaker['name']) ?></td>
                                                    <td><?= htmlspecialchars($speaker['designation']) ?></td>
                                                    <td><?= htmlspecialchars($speaker['email']) ?></td>
                                                    <td>
                                                        <a href="?id=<?= $speaker['id'] ?>"
                                                            class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="?delete=<?= $speaker['id'] ?>" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this speaker?')">
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($success_message); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($error_message); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="speaker_section_csrf_token"
                                    value="<?= $_SESSION['speaker_section_csrf_token']; ?>">

                                <?php if (!empty($speaker)): ?>
                                    <input type="hidden" name="id" value="<?= $speaker['id']; ?>">
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?= old('name', $speaker); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Designation</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="<?= old('designation', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="<?= old('email', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="<?= old('phone', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="<?= old('address', $speaker); ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Website</label>
                                            <input type="url" name="website" class="form-control"
                                                value="<?= old('website', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input type="text" name="facebook" class="form-control"
                                                value="<?= old('facebook', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Twitter</label>
                                            <input type="text" name="twitter" class="form-control"
                                                value="<?= old('twitter', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>LinkedIn</label>
                                            <input type="text" name="linkedin" class="form-control"
                                                value="<?= old('linkedin', $speaker); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Instagram</label>
                                            <input type="text" name="instagram" class="form-control"
                                                value="<?= old('instagram', $speaker); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Bio</label>
                                    <textarea name="bio" class="form-control"
                                        rows="8"><?= old('bio', $speaker ?? null); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" name="photo" class="form-control">
                                    <?php if (!empty($speaker['photo'])): ?>
                                        <div class="mt-2">
                                            <img src="../uploads/<?= htmlspecialchars($speaker['photo']) ?>"
                                                alt="Current photo" class="img-thumbnail" style="max-height: acc150px;">
                                            <p class="text-muted">Current photo</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="add_speaker_form" class="btn btn-primary">
                                        <?= empty($speaker) ? 'Add Speaker' : 'Update Speaker'; ?>
                                    </button>

                                    <?php if (!empty($speaker)): ?>
                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Cancel</a>
                                    <?php endif; ?>
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
<?php ob_end_flush(); ?>