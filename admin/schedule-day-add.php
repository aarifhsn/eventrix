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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_days_add_form'])) {
    try {
        if ($_POST['title'] == '') {
            throw new Exception("Day can not be empty");
        }
        if ($_POST['order_number'] == '') {
            throw new Exception("Order Number can not be empty");
        }
        if (!filter_var($_POST['order_number'], FILTER_VALIDATE_INT)) {
            throw new Exception("Order Number must be integer value");
        }

        $statement = $pdo->prepare("INSERT INTO schedule_days (title, date, order_number) VALUES (?,?,?)");
        $statement->execute([$_POST['title'], $_POST['date'], $_POST['order_number']]);

        $_SESSION['success_message'] = "Schedule Day added successfully";
        $_SESSION['form_success'] = true; // Add this to clear form fields
        header("location: " . ADMIN_URL . "schedule-day.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error adding schedule day: " . $e->getMessage();
        $_SESSION['form_success'] = false; // Form had errors
        header("location: " . ADMIN_URL . "schedule-day-add.php");
        exit;
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Schedule Day</h1>
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
                            <form action="" method="post">
                                <div class="form-group mb-3">
                                    <label>Title *</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Order</label>
                                    <input type="text" name="order_number" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"
                                        name="schedule_days_add_form">Submit</button>
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