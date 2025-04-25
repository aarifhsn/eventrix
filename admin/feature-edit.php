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
$featureData = fetchById($pdo, 'features', $_REQUEST['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feature_update_form'])) {
    try {
        $requiredFields = [
            'title' => 'Title',
            'price' => 'Content',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($_POST[$field])) {
                throw new Exception("$label is required");
            }
        }

        $statement = $pdo->prepare("UPDATE features SET 
            title = ?,
            price = ?,
            max_price = ?,
            item_order = ?
            WHERE id = ?"
        );

        $statement->execute([
            $_POST['title'],
            $_POST['price'],
            $_POST['max_price'],
            $_POST['item_order'],
            $_REQUEST['id'],
        ]);

        $success_message = "feature updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "feature.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "feature-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

// unset CSRF token
unset($_SESSION['csrf_token']);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit feature</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>feature.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $featureData['title']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Price *</label>
                                            <input type="text" name="price" class="form-control"
                                                value="<?php echo $featureData['price']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Max Price</label>
                                            <input type="text" name="max_price" class="form-control"
                                                value="<?php echo $featureData['max_price']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Item Order</label>
                                            <input type="text" name="item_order" class="form-control"
                                                value="<?php echo $featureData['item_order']; ?>">
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