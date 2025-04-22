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
$sponsorData = fetchById($pdo, 'sponsors', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sponsor_update_form'])) {
    try {

        if (empty($_POST['name'])) {
            throw new Exception("Name cannot be empty");
        }

        // Image upload logic
        try {
            $filename = uploadImage('featured_photo'); // Default input: photo, uploads/ folder
            echo "Uploaded successfully as $filename";
        } catch (Exception $e) {
            throw new Exception("Upload failed: " . $e->getMessage());
        }
        // Logo upload logic
        try {
            $logo = uploadImage('logo'); // Default input: photo, uploads/ folder
            echo "Uploaded successfully as $logo";
        } catch (Exception $e) {
            throw new Exception("Upload failed: " . $e->getMessage());
        }

        $statement = $pdo->prepare("UPDATE sponsors SET 
                            sponsor_category_id=?,
                            name=?,
                            title=?,
                            description=?,
                            address=?,
                            email=?,
                            phone=?,
                            website=?,
                            facebook=?,
                            twitter=?,
                            linkedin=?,
                            instagram=?,
                            map=?,
                            logo=?,
                            featured_photo=?
                            WHERE id=?"
        );

        $statement->execute([
            $_POST['sponsor_category_id'],
            $_POST['name'],
            $_POST['title'],
            $_POST['description'],
            $_POST['address'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['website'],
            $_POST['facebook'],
            $_POST['twitter'],
            $_POST['linkedin'],
            $_POST['instagram'],
            $_POST['map'],
            $filename,
            $_REQUEST['id']
        ]);

        $_SESSION['success_message'] = "Sponsor updated successfully!";
        header("location: " . ADMIN_URL . "sponsor.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("location: " . ADMIN_URL . "sponsor-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

$sponsorCategoryData = fetchAll($pdo, 'sponsor_categories', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Sponsor</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>sponsor.php" class="btn btn-primary"><i class="fas fa-eye"></i> View
                    All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="logo" value="<?php echo $sponsorData['logo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Logo</label>
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $sponsorData['logo']; ?>"
                                            alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Existing Featured Photo</label>
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $sponsorData['featured_photo']; ?>"
                                            alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Change Feature Photo</label>
                                    <div>
                                        <input type="file" name="featured_photo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo $sponsorData['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $sponsorData['title']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="<?php echo $sponsorData['phone']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Sponsor Category</label>
                                            <select name="sponsor_category_id" class="form-control">
                                                <option value="">-- Select sponsor category -- </option>
                                                <?php foreach ($sponsorCategoryData as $sponsorCategory) { ?>
                                                    <option value="<?php echo $sponsorCategory['id']; ?>"
                                                        <?php echo $sponsorCategory['id'] == $sponsorData['sponsor_category_id'] ? 'selected' : ''; ?>>
                                                        <?php echo $sponsorCategory['title']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control h_200" cols="30"
                                        rows="10"><?php echo $sponsorData['description']; ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label><Address></Address></label>
                                            <input type="text" name="address" class="form-control"
                                                value="<?php echo $sponsorData['address']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Website</label>
                                            <input type="text" name="website" class="form-control"
                                                value="<?php echo $sponsorData['website']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Facebook</label>
                                            <input type="text" name="facebook" class="form-control"
                                                value="<?php echo $sponsorData['facebook']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Twitter</label>
                                            <input type="text" name="twitter" class="form-control"
                                                value="<?php echo $sponsorData['twitter']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Linkedin</label>
                                            <input type="text" name="linkedin" class="form-control"
                                                value="<?php echo $sponsorData['linkedin']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Instagram</label>
                                            <input type="text" name="instagram" class="form-control"
                                                value="<?php echo $sponsorData['instagram']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Map</label>
                                            <input type="text" name="map" class="form-control"
                                                value="<?php echo $sponsorData['map']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="sponsor_update_form">Update</button>
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