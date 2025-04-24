<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Initialize
initMessages();

// fetch data
$postData = fetchById($pdo, 'posts', $_REQUEST['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_update_form'])) {
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

        // Image upload logic
        try {
            if (!empty($_FILES['photo']['name'])) {
                $photo = uploadImage('photo');
            } else {
                $photo = $_POST['photo'];  // this comes from hidden input field
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: blog-edit.php?id=" . $_REQUEST['id']);
            exit;
        }

        // Generate slug
        if (isset($_POST['slug']) && !empty($_POST['slug'])) {
            $_POST['slug'] = generateUniqueSlug($_POST['slug'], $pdo); // Sanitize user input
        } else {
            $_POST['slug'] = generateUniqueSlug($_POST['title'], $pdo); // Auto-generate from title
        }


        $statement = $pdo->prepare("UPDATE posts SET 
            title = ?,
            slug = ?,
            content = ?,
            date = ?,
            photo = ?
            WHERE id = ?"
        );

        $statement->execute([
            $_POST['title'],
            $_POST['slug'],
            $_POST['content'],
            $_POST['date'],
            $photo,
            $_REQUEST['id'],
        ]);

        $success_message = "Post updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "blog.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "blog-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

// unset CSRF token
unset($_SESSION['csrf_token']);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit post</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>post.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                <input type="hidden" name="photo" value="<?php echo $postData['photo']; ?>">

                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $postData['photo']; ?>"
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
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $postData['title']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Slug</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="<?php echo $postData['slug']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Content *</label>
                                            <textarea name="content" class="editor form-control h_200" cols="30"
                                                rows="10"><?php echo $postData['content']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Date *</label>
                                            <input type="date" name="date" class="form-control"
                                                value="<?php echo $postData['date']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="post_update_form">Update</button>
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