<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Initialize
initMessages();

// fetch data
$testimonialData = fetchById($pdo, 'testimonials', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['testimonial_update_form'])) {
    try {
        if (empty($_POST['name'])) {
            throw new Exception("Name cannot be empty");
        }
        if (empty($_POST['comment'])) {
            throw new Exception("Comment cannot be empty");
        }

        // Image upload logic
        try {
            if (!empty($_FILES['photo']['name'])) {
                $photo = uploadImage('photo');
            } else {
                $photo = $_POST['photo'];  // this comes from hidden input field
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: testimonial-edit.php?id=" . $_REQUEST['id']);
            exit;
        }

        $statement = $pdo->prepare("UPDATE testimonials SET 
            name = ?,
            designation = ?,
            comment = ?,
            photo = ?
            WHERE id = ?"
        );

        $statement->execute([
            $_POST['name'],
            $_POST['designation'],
            $_POST['comment'],
            $photo,
            $_REQUEST['id'],
        ]);

        $success_message = "Testimonial updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "/testimonial.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/testimonial-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

// unset CSRF token
unset($_SESSION['csrf_token']);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Testimonial</h1>
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
                                <input type="hidden" name="photo" value="<?php echo $testimonialData['photo']; ?>">

                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $testimonialData['photo']; ?>"
                                            alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Change Photo</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo $testimonialData['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Designation</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="<?php echo $testimonialData['designation']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Comment</label>
                                            <textarea name="comment" class="form-control h_200" cols="30"
                                                rows="10"><?php echo $testimonialData['comment']; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="testimonial_update_form">Update</button>
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