<?php

ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

checkAdminAuth();

// Initialize message variables
$success_message = '';
$error_message = '';

// Check for messages in session
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

$statement = $pdo->prepare("SELECT * FROM schedule_days WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$total = $statement->rowCount();
if (!$total) {
    header('location: ' . ADMIN_URL . 'schedule-day.php');
    exit;
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_days_update_form'])) {
    try {
        if ($_POST['title'] == '') {
            throw new Exception("Title can not be empty");
        }
        if ($_POST['order_number'] == '') {
            throw new Exception("Order Number can not be empty");
        }
        if (!filter_var($_POST['order_number'], FILTER_VALIDATE_INT)) {
            throw new Exception("Order must be integer value");
        }

        $statement = $pdo->prepare("UPDATE schedule_days SET title=?, date=?, order_number=? WHERE id=?");
        $statement->execute([
            $_POST['title'],
            $_POST['date'],
            $_POST['order_number'],
            $_REQUEST['id']
        ]);

        $_SESSION['success_message'] = "Schedule Day updated successfully";
        header("location: " . ADMIN_URL . "schedule-day.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error updating schedule day: " . $e->getMessage();
        header("location: " . ADMIN_URL . "schedule-day-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

// Fetch schedule day details
$result = fetchAll($pdo, 'schedule_days', 'id ASC');
?>


<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Schedule Day</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>schedule-day.php" class="btn btn-primary"><i class="fas fa-eye"></i>
                    View All</a>
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
                                <div class="form-group mb-3">
                                    <label>Title *</label>
                                    <input type="text" name="title" class="form-control"
                                        value="<?php echo $result[0]['title']; ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="<?php echo date("Y-m-d", strtotime($result[0]['date'])); ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Order Number</label>
                                    <input type="text" name="order_number" class="form-control"
                                        value="<?php echo $result[0]['order_number']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="schedule_days_update_form">Update</button>
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
<?php ob_end_flush(); ?>