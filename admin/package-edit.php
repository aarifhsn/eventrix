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
$packageData = fetchById($pdo, 'packages', $_REQUEST['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['package_update_form'])) {
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

        // Update Package
        $statement = $pdo->prepare("UPDATE packages SET 
            title = ?,
            price = ?,
            max_price = ?,
            item_order = ?
            WHERE id = ?"
        );

        // Update Features
        $featureStatement = $pdo->prepare("DELETE FROM feature_package WHERE package_id = ?");
        $featureStatement->execute([$_REQUEST['id']]);

        if (!empty($_POST['features'])) {
            $featureStatement = $pdo->prepare("INSERT INTO feature_package (package_id, feature_id) VALUES (?, ?)");

            foreach ($_POST['features'] as $featureId) {
                $featureStatement->execute([
                    $_REQUEST['id'],
                    $featureId,
                ]);
            }
        }
        $statement->execute([
            $_POST['title'],
            $_POST['price'],
            $_POST['max_price'],
            $_POST['item_order'],
            $_REQUEST['id'],
        ]);

        $success_message = "Package updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "package.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "package-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

// unset CSRF token
unset($_SESSION['csrf_token']);

// Fetch assigned features for this package
$packageId = $_REQUEST['id'];
$stmt = $pdo->prepare("SELECT feature_id FROM feature_package WHERE package_id = ?");
$stmt->execute([$packageId]);
$assignedFeatures = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'feature_id');

$features = fetchAll($pdo, 'features');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit package</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>package.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                                value="<?php echo $packageData['title']; ?>" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Price *</label>
                                            <input type="text" name="price" class="form-control"
                                                value="<?php echo $packageData['price']; ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Max Price</label>
                                            <input type="text" name="max_price" class="form-control"
                                                value="<?php echo $packageData['max_price']; ?>">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Item Order</label>
                                            <input type="text" name="item_order" class="form-control"
                                                value="<?php echo $packageData['item_order']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Features</label>
                                            <?php foreach ($features as $feature): ?>
                                                <?php $isChecked = in_array($feature['id'], $assignedFeatures); ?>
                                                <div class="form-group mb-3 px-4 fs-4">
                                                    <label>
                                                        <input type="checkbox" name="features[]"
                                                            value="<?php echo $feature['id']; ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                                                        <?php echo htmlspecialchars($feature['name']); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="package_update_form">Update</button>
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