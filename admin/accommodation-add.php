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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_accommodation_form'])) {
    try {

        if (empty($_POST['name'])) {
            throw new Exception("Name is required");
        }

        // Handle image upload
        try {
            $filename = uploadImage(); // Default input: photo, uploads/ folder
            echo "Uploaded successfully as $filename";
        } catch (Exception $e) {
            echo "Upload failed: " . $e->getMessage();
            exit;
        }

        $statement = $pdo->prepare("INSERT INTO accommodations (name, email, description, address, phone, website, photo) VALUES (?,?,?,?,?,?,?)");
        $statement->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['description'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['website'],
            $filename
        ]);

        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['description']);
        unset($_SESSION['address']);
        unset($_SESSION['phone']);
        unset($_SESSION['website']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "accommodation.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['description'] = $_POST['description'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['phone'] = $_POST['phone'];
        $_SESSION['website'] = $_POST['website'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "accommodation-add.php");
        exit;
    }
}

// Fetch all schedule days
$accommodations = fetchAll($pdo, 'accommodations', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add accommodation</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>accommodation.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                            <label>Email</label>
                                            <input type="text" name="email" class="form-control"
                                                value="<?php old('email'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Description *</label>
                                            <textarea name="description" class="form-control h_200" cols="30"
                                                rows="10"><?php old('description'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="<?php old('phone'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="<?php old('address'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Website</label>
                                            <input type="text" name="website" class="form-control"
                                                value="<?php old('website'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="add_accommodation_form">Submit</button>
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