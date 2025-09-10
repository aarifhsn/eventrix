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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package_form'])) {
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

        $statement = $pdo->prepare("INSERT INTO packages (title, price, max_price, item_order) VALUES (?,?,?,?)");
        $statement->execute([
            $_POST['title'],
            $_POST['price'],
            $_POST['max_price'],
            $_POST['item_order'],
        ]);

        // Get the inserted package ID
        $packageId = $pdo->lastInsertId();

        // Insert selected features (if any)
        if (!empty($_POST['features'])) {
            $featureStatement = $pdo->prepare("INSERT INTO feature_package (package_id, feature_id) VALUES (?, ?)");

            foreach ($_POST['features'] as $featureId) {
                $featureStatement->execute([$packageId, $featureId]);
            }
        }

        unset($_SESSION['title']);
        unset($_SESSION['price']);
        unset($_SESSION['max_price']);
        unset($_SESSION['item_order']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "/package.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['price'] = $_POST['price'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/package-add.php");
        exit;
    }
}

// Fetch all schedule days
$packages = fetchAll($pdo, 'packages', 'id ASC');
$features = fetchAll($pdo, 'features');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add package</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/package.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                                value="<?php echo old('title'); ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Price *</label>
                                            <input type="text" name="price" class="form-control"
                                                value="<?php echo old('price'); ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Max Price</label>
                                            <input type="text" name="max_price" class="form-control"
                                                value="<?php echo old('max_price'); ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Item Order</label>
                                            <input type="text" name="item_order" class="form-control"
                                                value="<?php echo old('item_order'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="my-2 px-4">Features</h4>
                                        <?php foreach ($features as $feature): ?>
                                            <div class="form-group mb-3 px-4 fs-4">
                                                <label>
                                                    <input type="checkbox" name="features[]"
                                                        value="<?php echo $feature['id']; ?>">
                                                    <?php echo $feature['name']; ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="add_package_form">Submit</button>
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