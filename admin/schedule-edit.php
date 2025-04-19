<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// fetch data
$scheduleData = fetchAll($pdo, 'schedules', 'item_order ASC');

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
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $newFileName = uploadImage($_FILES['photo'], 'uploads', ['jpg', 'jpeg', 'png', 'webp'], 2 * 1024 * 1024, $scheduleData['photo'] ?? '');

                $stmt = $pdo->prepare("UPDATE home_abouts SET photo = :photo WHERE id = :id");
                $stmt->execute([':photo' => $newFileName, ':id' => $scheduleData['id']]);
                $scheduleData['photo'] = $newFileName;
            }
        } catch (Exception $e) {
            $error_message = $e->getMessage();
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

        $_SESSION['success_message'] = "Schedule updated successfully";
        header("location: " . ADMIN_URL . "schedule.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "schedule-edit.php?id=" . $_REQUEST['id']);
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
                <a href="<?php echo ADMIN_URL; ?>schedule.php" class="btn btn-primary"><i class="fas fa-eye"></i> View
                    All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="current_photo"
                                    value="<?php echo $scheduleData[0]['photo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $scheduleData[0]['photo']; ?>"
                                            alt="" class="w_200">
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
                                                value="<?php echo $scheduleData[0]['name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $scheduleData[0]['title']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Select Schedule Day *</label>
                                            <select name="schedule_day_id" class="form-select">
                                                <?php
                                                foreach ($schedule_daysData as $row) {
                                                    ?>
                                                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $scheduleData[0]['schedule_day_id']) ? 'selected' : ''; ?>>
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
                                        rows="10"><?php echo $scheduleData[0]['description']; ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Location *</label>
                                            <input type="text" name="location" class="form-control"
                                                value="<?php echo $scheduleData[0]['location']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Time *</label>
                                            <input type="text" name="time" class="form-control"
                                                value="<?php echo $scheduleData[0]['time']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Order *</label>
                                            <input type="text" name="item_order" class="form-control"
                                                value="<?php echo $scheduleData[0]['item_order']; ?>">
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