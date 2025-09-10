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
$scheduleData = fetchById($pdo, 'schedules', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_update_form'])) {
    try {
        $requiredFields = [
            'name',
            'title',
            'location',
            'time',
            'item_order'
        ];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field cannot be empty");
            }
        }

        if (!filter_var($_POST['item_order'], FILTER_VALIDATE_INT)) {
            throw new Exception("Order must be integer value");
        }

        // Image upload logic
        try {
            $filename = uploadImage(); // Default input: photo, uploads/ folder
            echo "Uploaded successfully as $filename";
        } catch (Exception $e) {
            throw new Exception("Upload failed: " . $e->getMessage());
        }

        $statement = $pdo->prepare("UPDATE schedules SET 
                            schedule_day_id=?,name=?,title=?, description=?,location=?, time=?,  photo=?,
                            item_order=? WHERE id=?"
        );

        $statement->execute([
            $_POST['schedule_day_id'],
            $_POST['name'],
            $_POST['title'],
            $_POST['description'],
            $_POST['location'],
            $_POST['time'],
            $filename,
            $_POST['item_order'],
            $_REQUEST['id']
        ]);

        $_SESSION['success_message'] = "Schedule updated successfully!";
        header("location: " . ADMIN_URL . "/schedule.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("location: " . ADMIN_URL . "/schedule-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}
$schedule_daysData = fetchAll($pdo, 'schedule_days', 'date ASC');

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Schedule</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/schedule.php" class="btn btn-primary"><i class="fas fa-eye"></i> View
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
                                <input type="hidden" name="current_photo" value="<?php echo $scheduleData['photo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <?php
                                        if ($scheduleData['photo']) {
                                            ?>
                                            <img src="<?php echo ADMIN_URL . 'uploads/' . $scheduleData['photo']; ?>"
                                                width="200">
                                            <?php
                                        }
                                        ?>
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
                                                value="<?php echo $scheduleData['name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $scheduleData['title']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Select Schedule Day *</label>
                                            <select name="schedule_day_id" class="form-select">
                                                <?php
                                                foreach ($schedule_daysData as $row) {
                                                    ?>
                                                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $scheduleData['schedule_day_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $row['title']; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description *</label>
                                    <textarea name="description" class="form-control h_200" cols="30"
                                        rows="10"><?php echo $scheduleData['description']; ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Location *</label>
                                            <input type="text" name="location" class="form-control"
                                                value="<?php echo $scheduleData['location']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Time *</label>
                                            <input type="text" name="time" class="form-control"
                                                value="<?php echo $scheduleData['time']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Order *</label>
                                            <input type="text" name="item_order" class="form-control"
                                                value="<?php echo $scheduleData['item_order']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="schedule_update_form">Update</button>
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