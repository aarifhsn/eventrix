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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule_form'])) {
    try {
        $requiredFields = [
            'name' => 'Name',
            'title' => 'Title',
            'location' => 'Location',
            'time' => 'Time',
            'item_order' => 'Order'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($_POST[$field])) {
                throw new Exception("$label cannot be empty");
            }
        }
        if (!filter_var($_POST['item_order'], FILTER_VALIDATE_INT)) {
            throw new Exception("Order must be integer value");
        }

        // Handle image upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $mimeType = mime_content_type($fileTmp);
            $fileSize = $_FILES['photo']['size'];

            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($fileExt, $allowedExts) || !in_array($mimeType, $allowedMimes)) {
                throw new Exception("Invalid image format.");
            }

            if ($fileSize > 2 * 1024 * 1024) {
                throw new Exception("Image size must be under 2MB.");
            }

            if (!is_dir('uploads'))
                mkdir('uploads', 0755, true);

            if (!empty($bannerData['photo']))
                @unlink('uploads/' . $bannerData['photo']);

            $newFileName = uniqid('photo_', true) . '.' . $fileExt;
            move_uploaded_file($fileTmp, 'uploads/' . $newFileName);

            // Update the filename in the database
            $filename = $newFileName;
        }

        $statement = $pdo->prepare("INSERT INTO schedules (schedule_day_id,name,title,description,location, time,photo,item_order) VALUES (?,?,?,?,?,?,?,?)");
        $statement->execute([$_POST['schedule_day_id'], $_POST['name'], $_POST['title'], $_POST['description'], $_POST['location'], $_POST['time'], $filename, $_POST['item_order']]);

        $_SESSION['success_message'] = "Data insert is successful";
        unset($_SESSION['name']);
        unset($_SESSION['title']);
        unset($_SESSION['description']);
        unset($_SESSION['location']);
        unset($_SESSION['time']);
        unset($_SESSION['item_order']);


        header("location: " . ADMIN_URL . "schedule.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['description'] = $_POST['description'];
        $_SESSION['location'] = $_POST['location'];
        $_SESSION['time'] = $_POST['time'];
        $_SESSION['item_order'] = $_POST['item_order'];
        header("location: " . ADMIN_URL . "schedule-add.php");
        exit;
    }
}

// Fetch all schedule days
$scheduleDays = fetchAll($pdo, 'schedule_days', 'date ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Schedule</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>schedule.php" class="btn btn-primary"><i class="fas fa-eye"></i> View
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
                                    <label>Photo</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control" value="<?php if (isset($_SESSION['name'])) {
                                                echo $_SESSION['name'];
                                            } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control" value="<?php if (isset($_SESSION['title'])) {
                                                echo $_SESSION['title'];
                                            } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Select Schedule Day</label>
                                            <select name="schedule_day_id" class="form-select">
                                                <?php
                                                foreach ($scheduleDays as $row) {
                                                    ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control h_200" cols="30" rows="10"><?php if (isset($_SESSION['description'])) {
                                        echo $_SESSION['description'];
                                    } ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Location *</label>
                                            <input type="text" name="location" class="form-control" value="<?php if (isset($_SESSION['location'])) {
                                                echo $_SESSION['location'];
                                            } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Time *</label>
                                            <input type="text" name="time" class="form-control" value="<?php if (isset($_SESSION['time'])) {
                                                echo $_SESSION['time'];
                                            } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Order *</label>
                                            <input type="text" name="item_order" class="form-control" value="<?php if (isset($_SESSION['item_order'])) {
                                                echo $_SESSION['item_order'];
                                            } ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="add_schedule_form">Submit</button>
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