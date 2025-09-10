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
$featureData = fetchById($pdo, 'features', $_REQUEST['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feature_update_form'])) {
    try {
        if (empty($_POST['name'])) {
            throw new Exception("Name is required");
        }

        $statement = $pdo->prepare("UPDATE features SET 
            name = ?,
            feature_order = ?
            WHERE id = ?"
        );

        $statement->execute([
            $_POST['name'],
            $_POST['feature_order'],
            $_REQUEST['id'],
        ]);

        $success_message = "feature updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "/feature.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/feature-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit feature</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/feature.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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

                            <form action="" method="POST" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo $featureData['name']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>feature Order</label>
                                            <input type="text" name="feature_order" class="form-control"
                                                value="<?php echo $featureData['feature_order']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="feature_update_form">Update</button>
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