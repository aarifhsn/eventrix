<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Check for messages in session
initMessages();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_testimonial_form'])) {
    try {

        if (empty($_POST['name'])) {
            throw new Exception("Name is required");
        }

        if (empty($_POST['comment'])) {
            throw new Exception("Comments is required");
        }

        // Handle image upload
        try {
            $photo = uploadImage(); // Default input: photo, uploads/ folder
            echo "Uploaded successfully as $photo";
        } catch (Exception $e) {
            echo "Upload failed: " . $e->getMessage();
            exit;
        }

        $statement = $pdo->prepare("INSERT INTO testimonials (name, designation, comment, photo) VALUES (?,?,?,?)");
        $statement->execute([
            $_POST['name'],
            $_POST['designation'],
            $_POST['comment'],
            $photo
        ]);

        unset($_SESSION['name']);
        unset($_SESSION['designation']);
        unset($_SESSION['comment']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "/testimonial.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['designation'] = $_POST['designation'];
        $_SESSION['comment'] = $_POST['comment'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/testimonial-add.php");
        exit;
    }
}

// Fetch all schedule days
$testimonials = fetchAll($pdo, 'testimonials', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add testimonial</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/testimonial.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?php old('name'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Designation</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="<?php old('designation'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Comment *</label>
                                            <textarea name="comment" class="form-control h_200" cols="30"
                                                rows="10"><?php old('comment'); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="add_testimonial_form">Submit</button>
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