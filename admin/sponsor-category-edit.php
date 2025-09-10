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
$sponsorCategoryData = fetchById($pdo, 'sponsor_categories', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sponsor_category_update_form'])) {
    try {

        if (empty($_POST['title'])) {
            throw new Exception("Title cannot be empty");
        }

        $statement = $pdo->prepare("UPDATE sponsor_categories SET 
                            title=?, description=?
                            WHERE id=?"
        );

        $statement->execute([
            $_POST['title'],
            $_POST['description'],
            $_REQUEST['id']
        ]);

        $_SESSION['success_message'] = "Sponsor category updated successfully!";
        header("location: " . ADMIN_URL . "/sponsor-category.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("location: " . ADMIN_URL . "/sponsor-category-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Sponsor Category</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/sponsor-category.php" class="btn btn-primary"><i
                        class="fas fa-eye"></i> View
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $sponsorCategoryData['title']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control h_100" cols="30"
                                                rows="10"><?php echo $sponsorCategoryData['description']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="sponsor_category_update_form">Update</button>
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