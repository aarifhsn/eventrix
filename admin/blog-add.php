<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Check for messages in session
initMessages();

// Check if user is logged in
checkAdminAuth();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post_form'])) {
    try {

        $requiredFields = [
            'title' => 'Title',
            'content' => 'Content',
            'date' => 'Date',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($_POST[$field])) {
                throw new Exception("$label is required");
            }
        }

        // Handle image upload
        try {
            $photo = uploadImage(); // Default input: photo, uploads/ folder
            echo "Uploaded successfully as $photo";
        } catch (Exception $e) {
            echo "Upload failed: " . $e->getMessage();
            exit;
        }

        // Generate slug
        if (isset($_POST['slug']) && !empty($_POST['slug'])) {
            $_POST['slug'] = generateUniqueSlug($_POST['slug'], $pdo); // Sanitize user input
        } else {
            $_POST['slug'] = generateUniqueSlug($_POST['title'], $pdo); // Auto-generate from title
        }


        $statement = $pdo->prepare("INSERT INTO posts (title, slug, content, date, photo) VALUES (?,?,?,?,?)");
        $statement->execute([
            $_POST['title'],
            $_POST['slug'],
            $_POST['content'],
            $date = !empty($_POST['date']) && strtotime($_POST['date']) !== false
            ? date('Y-m-d H:i:s', strtotime($_POST['date']))
            : date('Y-m-d H:i:s'),
            $photo
        ]);

        unset($_SESSION['title']);
        unset($_SESSION['slug']);
        unset($_SESSION['content']);
        unset($_SESSION['date']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "blog.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['slug'] = $_POST['slug'];
        $_SESSION['content'] = $_POST['content'];
        $_SESSION['date'] = $_POST['date'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "blog-add.php");
        exit;
    }
}

// Fetch all schedule days
$posts = fetchAll($pdo, 'posts', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add post</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>blog.php" class="btn btn-primary"><i class="fas fa-eye"></i>
                    View
                    All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo displaySuccess($success_message); ?>

                            <?php echo displayError($error_message); ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label>Photo *</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php old('title'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Slug</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="<?php old('slug'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Content *</label>
                                            <textarea name="content" class="form-control h_200" cols="30"
                                                rows="10"><?php old('content'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Date</label>
                                            <input type="datetime-local" name="date" class="form-control"
                                                value="<?= isset($_SESSION['date']) ? date('Y-m-d\TH:i', strtotime($_SESSION['date'])) : date('Y-m-d\TH:i') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="add_post_form">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>