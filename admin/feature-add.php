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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_feature_form'])) {
    try {
        if (empty($_POST['name'])) {
            throw new Exception("Name is required");
        }

        $statement = $pdo->prepare("INSERT INTO features (name, feature_order) VALUES (?,?)");
        $statement->execute([
            $_POST['name'],
            $_POST['feature_order'],
        ]);

        unset($_SESSION['name']);
        unset($_SESSION['feature_order']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "/feature.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['feature_order'] = $_POST['feature_order'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/feature-add.php");
        exit;
    }
}

// Fetch all schedule days
$features = fetchAll($pdo, 'features', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add feature</h1>
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

                            <form action="" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo old('name'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Feature Order</label>
                                            <input type="text" name="feature_order" class="form-control"
                                                value="<?php echo old('feature_order'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="add_feature_form">Submit</button>
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