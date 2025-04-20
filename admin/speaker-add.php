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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_speaker_form'])) {
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

        $statement = $pdo->prepare("INSERT INTO speakers (name, designation, bio, address, email, phone, website, facebook, twitter, linkedin, instagram, photo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([
            $_POST['name'],
            $_POST['designation'],
            $_POST['bio'],
            $_POST['address'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['website'],
            $_POST['facebook'],
            $_POST['twitter'],
            $_POST['linkedin'],
            $_POST['instagram'],
            $filename
        ]);

        unset($_SESSION['name']);
        unset($_SESSION['designation']);
        unset($_SESSION['bio']);
        unset($_SESSION['address']);
        unset($_SESSION['email']);
        unset($_SESSION['phone']);
        unset($_SESSION['website']);
        unset($_SESSION['facebook']);
        unset($_SESSION['twitter']);
        unset($_SESSION['linkedin']);
        unset($_SESSION['instagram']);

        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "speaker.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['designation'] = $_POST['designation'];
        $_SESSION['bio'] = $_POST['bio'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['phone'] = $_POST['phone'];
        $_SESSION['website'] = $_POST['website'];
        $_SESSION['facebook'] = $_POST['facebook'];
        $_SESSION['twitter'] = $_POST['twitter'];
        $_SESSION['linkedin'] = $_POST['linkedin'];
        $_SESSION['instagram'] = $_POST['instagram'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "speaker-add.php");
        exit;
    }
}

// Fetch all schedule days
$speakers = fetchAll($pdo, 'speakers', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Speaker</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>speaker.php" class="btn btn-primary"><i class="fas fa-eye"></i> View
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
                                            <label>Designation *</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="<?php old('designation'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Biography</label>
                                    <textarea name="biography" class="form-control h_200" cols="30"
                                        rows="10"><?php old('biography'); ?></textarea>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Facebook</label>
                                            <input type="text" name="facebook" class="form-control"
                                                value="<?php old('facebook'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Twitter</label>
                                            <input type="text" name="twitter" class="form-control"
                                                value="<?php old('twitter'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Linkedin</label>
                                            <input type="text" name="linkedin" class="form-control"
                                                value="<?php old('linkedin'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Instagram</label>
                                            <input type="text" name="instagram" class="form-control"
                                                value="<?php old('instagram'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="add_speaker_form">Submit</button>
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