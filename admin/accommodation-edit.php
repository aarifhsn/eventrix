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
$accommodationData = fetchById($pdo, 'accommodations', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accommodation_update_form'])) {
    try {
        if (empty($_POST['name'])) {
            throw new Exception("Name cannot be empty");
        }

        // Image upload logic
        try {
            if (!empty($_FILES['photo']['name'])) {
                $filename = uploadImage('photo');
            } else {
                $filename = $_POST['photo'];  // this comes from hidden input field
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: accommodation-edit.php?id=" . $_REQUEST['id']);
            exit;
        }

        $statement = $pdo->prepare("UPDATE accommodations SET 
            name = ?,
            email = ?,
            description = ?,
            address = ?,
            phone = ?,
            website = ?,
            photo = ?
            WHERE id = ?"
        );

        $statement->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['description'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['website'],
            $filename,
            $_REQUEST['id'],
        ]);

        $success_message = "accommodation updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "/accommodation.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/accommodation-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

// unset CSRF token
unset($_SESSION['csrf_token']);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit accommodation</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/accommodation.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                <input type="hidden" name="photo" value="<?php echo $accommodationData['photo']; ?>">

                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $accommodationData['photo']; ?>"
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
                                                value="<?php echo $accommodationData['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Email</label>
                                            <input type="text" name="email" class="form-control"
                                                value="<?php echo $accommodationData['email']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Description</label>
                                            <input type="text" name="description" class="form-control"
                                                value="<?php echo $accommodationData['description']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="<?php echo $accommodationData['phone']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="<?php echo $accommodationData['address']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Website</label>
                                            <input type="text" name="website" class="form-control"
                                                value="<?php echo $accommodationData['website']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="accommodation_update_form">Update</button>
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