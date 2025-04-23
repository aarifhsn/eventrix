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
$organizerData = fetchById($pdo, 'organizers', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['organizer_update_form'])) {
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
            header("Location: organizer-edit.php?id=" . $_REQUEST['id']);
            exit;
        }

        $statement = $pdo->prepare("UPDATE organizers SET 
                            name=?, email=?, bio=?, designation=?, address=?, phone=?, website=?, facebook=?, twitter=?, linkedin=?, instagram=?, photo=?
                            WHERE id=?"
        );

        $statement->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['bio'],
            $_POST['designation'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['website'],
            $_POST['facebook'],
            $_POST['twitter'],
            $_POST['linkedin'],
            $_POST['instagram'],
            $filename,
            $_REQUEST['id']
        ]);

        $success_message = "organizer updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "organizer.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "organizer-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit organizer</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>organizer.php" class="btn btn-primary"><i class="fas fa-eye"></i> View
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
                                <input type="hidden" name="photo" value="<?php echo $organizerData['photo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $organizerData['photo']; ?>"
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
                                                value="<?php echo $organizerData['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Email</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="<?php echo $organizerData['email']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Designation</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="<?php echo $organizerData['designation']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Biography</label>
                                    <textarea name="bio" class="form-control h_200" cols="30"
                                        rows="10"><?php echo $organizerData['bio']; ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Email</label>
                                            <input type="text" name="email" class="form-control"
                                                value="<?php echo $organizerData['email']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="<?php echo $organizerData['phone']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="<?php echo $organizerData['address']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Website</label>
                                            <input type="text" name="website" class="form-control"
                                                value="<?php echo $organizerData['website']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Facebook</label>
                                            <input type="text" name="facebook" class="form-control"
                                                value="<?php echo $organizerData['facebook']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Twitter</label>
                                            <input type="text" name="twitter" class="form-control"
                                                value="<?php echo $organizerData['twitter']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Linkedin</label>
                                            <input type="text" name="linkedin" class="form-control"
                                                value="<?php echo $organizerData['linkedin']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Instagram</label>
                                            <input type="text" name="instagram" class="form-control"
                                                value="<?php echo $organizerData['instagram']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="organizer_update_form">Update</button>
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