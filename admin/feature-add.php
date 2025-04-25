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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_feature_form'])) {
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

        $statement = $pdo->prepare("INSERT INTO features (title, price, max_price, item_order) VALUES (?,?,?,?)");
        $statement->execute([
            $_POST['title'],
            $_POST['price'],
            $_POST['max_price'],
            $_POST['item_order'],
        ]);

        unset($_SESSION['title']);
        unset($_SESSION['price']);
        unset($_SESSION['max_price']);
        unset($_SESSION['item_order']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "feature.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['price'] = $_POST['price'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "feature-add.php");
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
                            <form action="" method="post">

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
                                            <label>Price *</label>
                                            <input type="text" name="price" class="form-control"
                                                value="<?php old('price'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Max Price</label>
                                            <input type="text" name="max_price" class="form-control"
                                                value="<?php old('max_price'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Item Order</label>
                                            <input type="text" name="item_order" class="form-control"
                                                value="<?php old('item_order'); ?>">
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